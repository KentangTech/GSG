"use client";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Navigation from "../components/Navigation";
import Footer from "../components/footer";
import styles from "../page.module.css";
import '../globals.css';

export default function NewsPage() {
  const router = useRouter();
  const [newsList, setNewsList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchQuery, setSearchQuery] = useState("");

  // Dummy data berita
  const dummyNews = [
    {
      id: 1,
      title: "Judul Berita 1",
      content: "Isi berita 1...",
      image: "/image/news1.jpg",
    },
    {
      id: 2,
      title: "Judul Berita 2",
      content: "Isi berita 2...",
      image: "/image/news2.jpg",
    },
    {
      id: 3,
      title: "Judul Berita 3",
      content: "Isi berita 3...",
      image: "/image/news3.jpg",
    },
    {
      id: 4,
      title: "Judul Berita 4",
      content: "Isi berita 4...",
      image: "/image/news4.jpg",
    },
    {
      id: 5,
      title: "Judul Berita 5",
      content: "Isi berita 5...",
      image: "/image/news5.jpg",
    },
    {
      id: 6,
      title: "Judul Berita 6",
      content: "Isi berita 6...",
      image: "/image/news6.jpg",
    },
    {
      id: 7,
      title: "Judul Berita 7",
      content: "Isi berita 7...",
      image: "/image/news7.jpg",
    },
    {
      id: 8,
      title: "Judul Berita 8",
      content: "Isi berita 8...",
      image: "/image/news8.jpg",
    },
    {
      id: 9,
      title: "Judul Berita 9",
      content: "Isi berita 9...",
      image: "/image/news9.jpg",
    },
    {
      id: 10,
      title: "Judul Berita 10",
      content: "Isi berita 10...",
      image: "/image/news10.jpg",
    },
  ];

  // Tambahkan flag imageLoaded dan imageError
  useEffect(() => {
    const timer = setTimeout(() => {
      const newsWithFlags = dummyNews.map((news) => ({
        ...news,
        imageLoaded: false,
        imageError: false,
      }));
      setNewsList(newsWithFlags);
      setLoading(false);
    }, 1000);

    return () => clearTimeout(timer);
  }, []);

  // Timeout untuk gambar yang tidak dimuat dalam 2 menit
  useEffect(() => {
    const timeoutIds = newsList
      .filter((news) => news.image && !news.imageLoaded && !news.imageError)
      .map((news) =>
        setTimeout(() => {
          setNewsList((prev) =>
            prev.map((n) => (n.id === news.id ? { ...n, imageError: true } : n))
          );
        }, 120000)
      );

    return () => {
      timeoutIds.forEach((id) => clearTimeout(id));
    };
  }, [newsList]);

  const handleImageLoad = (id) => {
    setNewsList((prev) =>
      prev.map((news) =>
        news.id === id
          ? { ...news, imageLoaded: true, imageError: false }
          : news
      )
    );
  };

  const handleImageError = (id) => {
    setNewsList((prev) =>
      prev.map((news) =>
        news.id === id ? { ...news, imageError: true } : news
      )
    );
  };

  // Hitung jumlah berita yang ditampilkan
  const newsPerPage = 5;
  const filteredNews = newsList.filter(
    (news) =>
      news.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      news.content.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const totalPages = Math.ceil(filteredNews.length / newsPerPage);
  const currentNews = filteredNews.slice(
    (currentPage - 1) * newsPerPage,
    currentPage * newsPerPage
  );

  const goToNextPage = () => {
    if (currentPage < totalPages) {
      setCurrentPage(currentPage + 1);
    }
  };

  const goToPrevPage = () => {
    if (currentPage > 1) {
      setCurrentPage(currentPage - 1);
    }
  };

  // Hitung start dan end
  const start = (currentPage - 1) * newsPerPage + 1;
  const end = Math.min(currentPage * newsPerPage, filteredNews.length);
  const totalNews = filteredNews.length;

  if (loading) {
    return (
      <section style={{ padding: "60px 20px", textAlign: "center" }}>
        <h2 style={{ marginBottom: "2rem" }}>Latest News</h2>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            gap: "1.5rem",
            maxWidth: 900,
            margin: "0 auto",
          }}
        >
          {[...Array(5)].map((_, idx) => (
            <div
              key={`skeleton-${idx}`}
              style={{
                display: "flex",
                flexDirection: "row",
                gap: "1rem",
                backgroundColor: "#f0f0f0",
                borderRadius: "15px",
                boxShadow: "0 4px 10px rgba(0,0,0,0.05)",
                padding: "1rem",
                alignItems: "center",
              }}
            >
              <div
                style={{
                  width: "120px",
                  height: "90px",
                  backgroundColor: "#ddd",
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
              <div style={{ flex: 1 }}>
                <div
                  style={{
                    height: 20,
                    backgroundColor: "#ddd",
                    borderRadius: "4px",
                    marginBottom: "0.5rem",
                  }}
                ></div>
                <div
                  style={{
                    height: 40,
                    backgroundColor: "#ddd",
                    borderRadius: "4px",
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
          onClick={() => window.location.reload()}
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

  return (
    <>
      <Navigation />

      <main className={styles.main}>
        <section
          style={{
            backgroundColor: "white",
            padding: "40px 20px",
            color: "black",
            minHeight: "100vh",
            boxSizing: "border-box",
          }}
        >
          <div
            style={{
              maxWidth: 900,
              margin: "0 auto",
              display: "flex",
              flexDirection: "column",
              gap: "1.5rem",
            }}
          >
            <h2
              style={{
                textAlign: "center",
                marginBottom: "2rem",
                color: "black",
              }}
            >
              Latest News
            </h2>

            {/* Search Bar */}
            <div
              style={{
                display: "flex",
                justifyContent: "center",
              }}
            >
              <input
                type="text"
                placeholder="Cari berita..."
                value={searchQuery}
                onChange={(e) => {
                  setSearchQuery(e.target.value);
                  setCurrentPage(1);
                }}
                style={{
                  padding: "0.6rem 1.2rem",
                  width: "100%",
                  maxWidth: "600px",
                  borderRadius: "5px",
                  border: "1px solid #ccc",
                  fontSize: "1rem",
                }}
              />
            </div>

            {/* News List */}
            {currentNews.map((news) => {
              const slug = news.title
                .toLowerCase()
                .replace(/\s+/g, "-")
                .replace(/[^\w\-]+/g, "");

              return (
                <article
                  key={news.id}
                  style={{
                    display: "flex",
                    flexDirection: "row",
                    backgroundColor: "white",
                    color: "#222",
                    borderRadius: "15px",
                    boxShadow: "0 8px 20px rgba(0,0,0,0.1)",
                    padding: "1rem",
                    position: "relative",
                    overflow: "hidden",
                    cursor: "pointer",
                    transition: "transform 0.3s ease",
                  }}
                  onClick={() => router.push(`/news/${slug}`)}
                  onMouseEnter={(e) =>
                    (e.currentTarget.style.boxShadow =
                      "0 12px 24px rgba(0,0,0,0.2)")
                  }
                  onMouseLeave={(e) =>
                    (e.currentTarget.style.boxShadow =
                      "0 8px 20px rgba(0,0,0,0.1)")
                  }
                >
                  {/* Gambar Berita */}
                  <div
                    style={{
                      width: "120px",
                      height: "90px",
                      backgroundColor: "#ddd",
                      position: "relative",
                      overflow: "hidden",
                      borderRadius: "10px",
                    }}
                  >
                    {/* Loader SVG */}
                    {!news.imageLoaded && !news.imageError && (
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
                          zIndex: 1,
                        }}
                      />
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
                          backgroundColor: "#f8d7da",
                          padding: "10px",
                          borderRadius: "10px",
                          fontSize: "0.9rem",
                          fontWeight: "bold",
                          zIndex: 2,
                        }}
                      >
                        Gambar tidak tersedia
                      </div>
                    )}

                    {/* Gambar Berita */}
                    {news.image && !news.imageError && (
                      <img
                        src={news.image}
                        alt={news.title}
                        style={{
                          width: "100%",
                          height: "100%",
                          objectFit: "cover",
                          opacity: news.imageLoaded ? 1 : 0,
                          transition: "opacity 0.3s ease",
                        }}
                        loading="lazy"
                        onLoad={() => handleImageLoad(news.id)}
                        onError={() => handleImageError(news.id)}
                      />
                    )}
                  </div>

                  {/* Teks Berita */}
                  <div style={{ flex: 1, paddingLeft: "1rem" }}>
                    <h3
                      style={{
                        margin: 0,
                        fontSize: "1.1rem",
                        fontWeight: "bold",
                        marginBottom: "0.5rem",
                      }}
                    >
                      {news.title}
                    </h3>
                    <p
                      style={{
                        fontSize: "0.95rem",
                        color: "#444",
                        overflow: "hidden",
                        WebkitLineClamp: 3,
                        WebkitBoxOrient: "vertical",
                        display: "-webkit-box",
                      }}
                    >
                      {news.content}
                    </p>
                  </div>
                </article>
              );
            })}

            {/* Pagination */}
            <div
              style={{
                display: "flex",
                justifyContent: "center",
                gap: "1rem",
                marginTop: "2rem",
              }}
            >
              <button
                onClick={goToPrevPage}
                disabled={currentPage === 1}
                style={{
                  padding: "0.5rem 1rem",
                  backgroundColor: currentPage === 1 ? "#ccc" : "#0d6efd",
                  color: "white",
                  border: "none",
                  borderRadius: "5px",
                  cursor: currentPage === 1 ? "not-allowed" : "pointer",
                }}
              >
                ← Sebelumnya
              </button>
              {/* Keterangan jumlah berita */}
              <div
                style={{
                  textAlign: "center",
                  fontSize: "0.95rem",
                  color: "#555",
                }}
              >
                Menampilkan <strong>{start}</strong>–<strong>{end}</strong> dari{" "}
                <strong>{totalNews}</strong> berita
              </div>
              <button
                onClick={goToNextPage}
                disabled={currentPage === totalPages}
                style={{
                  padding: "0.5rem 1rem",
                  backgroundColor:
                    currentPage === totalPages ? "#ccc" : "#0d6efd",
                  color: "white",
                  border: "none",
                  borderRadius: "5px",
                  cursor:
                    currentPage === totalPages ? "not-allowed" : "pointer",
                }}
              >
                Selanjutnya →
              </button>
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </>
  );
}
