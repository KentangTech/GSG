"use client";
import { useEffect, useState } from "react";

export default function NewsSection() {
  const [newsList, setNewsList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchNews = async () => {
      try {
        const res = await fetch("http://127.0.0.1:8000/api/news");
        if (!res.ok) {
          throw new Error(`Gagal mengambil data: ${res.status} ${res.statusText}`);
        }
        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          throw new Error("Respon bukan JSON");
        }
        const data = await res.json();
        if (!Array.isArray(data)) {
          throw new Error("Format data tidak valid");
        }
        const dataWithFlags = data.slice(0, 6).map((news) => ({
          ...news,
          imageLoaded: false,
          imageError: false,
        }));
        setNewsList(dataWithFlags);
      } catch (err) {
        console.error("Fetch error:", err.message);
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchNews();
  }, []);

  useEffect(() => {
    const timeoutIds = newsList
      .filter((news) => news.image && !news.imageLoaded && !news.imageError)
      .map((news) =>
        setTimeout(() => {
          setNewsList((prevList) =>
            prevList.map((n) =>
              n.id === news.id ? { ...n, imageError: true } : n
            )
          );
        }, 120000)
      );

    return () => {
      timeoutIds.forEach((id) => clearTimeout(id));
    };
  }, [newsList]);

  const handleImageLoad = (id) => {
    setNewsList((prevList) =>
      prevList.map((news) =>
        news.id === id ? { ...news, imageLoaded: true, imageError: false } : news
      )
    );
  };

  const handleImageError = (id) => {
    setNewsList((prevList) =>
      prevList.map((news) =>
        news.id === id ? { ...news, imageError: true } : news
      )
    );
  };

  const retry = () => {
    setLoading(true);
    const updatedNews = newsList.map((news) => ({
      ...news,
      imageLoaded: false,
      imageError: false,
    }));
    setNewsList(updatedNews);
  };

  if (loading && newsList.length === 0) {
    return (
      <section style={{ padding: "60px 20px", textAlign: "center" }}>
        <h2 style={{ marginBottom: "2rem" }}>Latest News</h2>
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "repeat(3, 1fr)",
            gap: "1.5rem",
            maxWidth: 900,
            margin: "0 auto",
          }}
        >
          {[...Array(6)].map((_, idx) => (
            <div
              key={`skeleton-${idx}`}
              style={{
                backgroundColor: "#f0f0f0",
                borderRadius: "15px",
                boxShadow: "0 4px 10px rgba(0,0,0,0.05)",
                padding: "1rem",
              }}
            >
              <div
                style={{
                  width: "100%",
                  height: 150,
                  backgroundColor: "#ddd",
                  borderRadius: 10,
                  marginBottom: "0.75rem",
                  position: "relative",
                }}
              >
                <img
                  src="/icons/Lazy-Loading.gif"
                  alt="Loading"
                  style={{
                    position: "absolute",
                    top: "50%",
                    left: "50%",
                    transform: "translate(-50%, -50%)",
                    width: "50%",
                    height: "50%",
                  }}
                />
              </div>
              <div
                style={{
                  height: 20,
                  backgroundColor: "#ddd",
                  borderRadius: 4,
                  marginBottom: "0.5rem",
                }}
              ></div>
              <div
                style={{
                  height: 60,
                  display: "flex",
                  justifyContent: "space-between",
                  flexDirection: "column",
                }}
              >
                <div
                  style={{
                    height: 10,
                    backgroundColor: "#ddd",
                    borderRadius: 4,
                    marginBottom: 5,
                  }}
                ></div>
                <div
                  style={{
                    height: 10,
                    backgroundColor: "#ddd",
                    borderRadius: 4,
                    marginBottom: 5,
                  }}
                ></div>
                <div
                  style={{
                    height: 10,
                    backgroundColor: "#ddd",
                    borderRadius: 4,
                  }}
                ></div>
              </div>
            </div>
          ))}
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section
        style={{
          padding: "60px 20px",
          textAlign: "center",
          backgroundColor: "#fff0f0",
        }}
      >
        <h3 style={{ color: "red" }}>Terjadi kesalahan</h3>
        <p>{error}</p>
        <button
          onClick={retry}
          style={{
            padding: "0.5rem 1rem",
            backgroundColor: "#0d6efd",
            color: "white",
            border: "none",
            borderRadius: "5px",
            cursor: "pointer",
          }}
        >
          Coba Lagi
        </button>
      </section>
    );
  }

  if (newsList.length === 0) {
    return (
      <section style={{ padding: "60px 20px", textAlign: "center" }}>
        <h3>Tidak ada berita tersedia.</h3>
        <p>Silakan cek kembali nanti.</p>
      </section>
    );
  }

  return (
    <section
      style={{
        backgroundColor: "white",
        padding: "40px 20px",
        marginTop: "120px",
        color: "black",
        minHeight: "100vh",
        boxSizing: "border-box",
      }}
    >
      <div
        style={{
          maxWidth: 900,
          margin: "0 auto",
          display: "grid",
          gridTemplateColumns: "repeat(auto-fit, minmax(280px, 1fr))",
          gap: "1.5rem",
        }}
      >
        <h2
          style={{
            gridColumn: "1 / -1",
            textAlign: "center",
            marginBottom: "2rem",
            color: "black",
          }}
        >
          Latest News
        </h2>

        {newsList.map((news) => (
          <article
            key={news.id}
            style={{
              position: "relative",
              backgroundColor: "white",
              borderRadius: "15px",
              boxShadow: "0 8px 20px rgba(0,0,0,0.3)",
              padding: "1rem",
              minHeight: "250px",
              cursor: "pointer",
              overflow: "hidden",
              backgroundImage:
                news.image && news.imageLoaded && !news.imageError
                  ? `url(http://localhost:8000/storage/${news.image})`
                  : "none",
              backgroundSize: "cover",
              backgroundPosition: "center",
              display: "flex",
              flexDirection: "column",
              justifyContent: "flex-end",
              transition: "transform 0.3s ease, box-shadow 0.3s ease",
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.boxShadow = "0 12px 30px rgba(0,0,0,0.2)";
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.boxShadow = "0 8px 20px rgba(0,0,0,0.3)";
            }}
            onClick={() => {
              window.location.href = `/news/${news.title}`;
            }}
          >
            {/* Overlay Transparan untuk Gambar */}
            <div
              style={{
                position: "absolute",
                inset: 0,
                background:
                  news.image && news.imageLoaded && !news.imageError
                    ? "rgba(255, 255, 255, 0)"
                    : "transparent",
                zIndex: 1,
                borderRadius: "15px",
              }}
            />

            {/* Loader SVG */}
            {!news.imageLoaded && !news.imageError && (
              <div
                style={{
                  position: "absolute",
                  top: "50%",
                  left: "50%",
                  transform: "translate(-50%, -50%)",
                  width: "50%",
                  height: "50%",
                  zIndex: 2,
                }}
              >
                <img
                  src="/icons/Lazy-Loading.gif"
                  alt="Loading"
                  style={{
                    width: "100%",
                    height: "100%",
                    display: "block",
                  }}
                  onLoad={() => handleImageLoad(news.id)}
                  onError={() => handleImageError(news.id)}
                />
              </div>
            )}

            {/* Pesan Error */}
            {!news.imageLoaded && news.imageError && (
              <div
                style={{
                  position: "absolute",
                  top: "50%",
                  left: "50%",
                  transform: "translate(-50%, -50%)",
                  width: "90%",
                  textAlign: "center",
                  color: "#721c24",
                  backgroundColor: "#7c7a7a2c",
                  padding: "10px",
                  borderRadius: "10px",
                  fontSize: "0.9rem",
                  fontWeight: "bold",
                  zIndex: 2,
                }}
              >
                Data tidak bisa dimuat
              </div>
            )}

            {/* Judul dan Deskripsi */}
            <div
              style={{
                position: "relative",
                zIndex: 3,
                padding: "1rem",
                background:
                  news.image && news.imageLoaded && !news.imageError
                    ? "rgba(27, 26, 26, 0)"
                    : "white",
                borderRadius: "10px",
              }}
            >
              <h3
                style={{
                  margin: 0,
                  fontSize: "1.1rem",
                  fontWeight: "bold",
                  marginBottom: "0.5rem",
                  color: "rgba(255, 255, 255, 1)",
                }}
              >
                {news.title}
              </h3>
            </div>
          </article>
        ))}

        <div
          style={{
            gridColumn: "1 / -1",
            textAlign: "center",
            marginTop: "2rem",
          }}
        >
          <button
            style={{
              padding: "0.6rem 1.2rem",
              backgroundColor: "#0d6efd",
              color: "white",
              border: "none",
              borderRadius: "5px",
              fontSize: "1rem",
              fontWeight: "bold",
              cursor: "pointer",
              transition: "background-color 0.3s ease",
            }}
            onMouseEnter={(e) =>
              (e.currentTarget.style.backgroundColor = "#084cd9")
            }
            onMouseLeave={(e) =>
              (e.currentTarget.style.backgroundColor = "#0d6efd")
            }
            onClick={() => {
              window.location.href = "/news";
            }}
          >
            Selengkapnya
          </button>
        </div>
      </div>
    </section>
  );
}