const API_BASE = process.env.NEXT_PUBLIC_LARAVEL_API;

if (!process.env.NEXT_PUBLIC_LARAVEL_API) {
  console.warn('Environment variable NEXT_PUBLIC_LARAVEL_API belum diatur');
}

const fetchData = async (endpoint) => {
  if (!endpoint) throw new Error('Endpoint tidak boleh kosong');

  const baseUrl = API_BASE.replace(/\/+$/, '');
  const cleanEndpoint = endpoint.replace(/^\/+/, '');
  const url = `${baseUrl}/${cleanEndpoint}`;

  try {
    const response = await fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      cache: 'no-store',
    });

    if (!response.ok) throw new Error(`HTTP ${response.status}`);

    const contentType = response.headers.get('content-type');
    if (!contentType?.includes('application/json')) {
      const text = await response.text();
      console.error('Bukan JSON:', text);
      throw new Error('Respons bukan JSON. Mungkin halaman login.');
    }

    return await response.json();
  } catch (error) {
    if (error.name === 'TypeError') {
      throw new Error('Tidak dapat terhubung ke server. Periksa koneksi atau URL API.');
    }
    throw new Error(`Gagal memuat  ${error.message}`);
  }
};

export { fetchData };