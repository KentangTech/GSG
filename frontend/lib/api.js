const createHash = (input) => {
  if (!input || typeof input !== 'string') return '0';
  let hash = 5381;
  for (let i = 0; i < input.length; i++) {
    hash = (hash * 33 + input.charCodeAt(i)) | 0;
  }
  return (hash >>> 0).toString(36);
};

const API_BASE = process.env.NEXT_PUBLIC_LARAVEL_API;

if (!API_BASE) {
  console.warn('NEXT_PUBLIC_LARAVEL_API belum diatur di environment');
}

const fetchData = async (endpoint) => {
  if (!endpoint) {
    throw new Error('Endpoint tidak boleh kosong');
  }

  const timestamp = Date.now();
  const uniqueString = `${endpoint}@${timestamp}`;
  const hash = createHash(uniqueString);
  const url = `${API_BASE}/${endpoint.replace(/^\/+/, '')}`;

  try {
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-Request-Hash': hash,
        'X-Timestamp': timestamp.toString(),
      },
      cache: 'no-store',
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error(`HTTP ${response.status} - ${endpoint}:`, errorText);
      throw new Error(`Gagal memuat data: ${response.status}`);
    }

    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      throw new Error('Respons bukan JSON');
    }

    return await response.json();
  } catch (error) {
    if (error.name === 'TypeError') {
      console.error('Network error:', error);
      throw new Error('Tidak dapat terhubung ke server. Periksa koneksi atau URL API.');
    }
    throw error;
  }
};

export { fetchData };