"use client";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Footer from "@/app/components/footer";
import RelatedNews from "./RelatedNews";
import Navigation from "@/app/components/NavigationALT";
import { fetchData } from "@/lib/api";
import "@/app/globals.css";

export default function NewsDetail({ params }) {
  const router = useRouter();
  const { slug } = params;

  const [news, setNews] = useState(null);
  const [loading, setLoading] = useState(true);
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);

  useEffect(() => {
    if (!slug) return;

    const loadNews = async () => {
      try {
        setLoading(true);
        const data = await fetchData(`news/${slug}`);

        // Format data dari API
        const formattedNews = {
          title: data.title,
          content: data.content || data.desc || data.excerpt || "<p>Belum ada konten.</p>",
          image: data.image?.startsWith("http")
            ? data.image
            : `${process.env.NEXT_PUBLIC_LARAVEL_API}/${data.image}`,
          date: new Date(data.created_at).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
          }),
          category: data.category || "Berita Terbaru",
        };

        setNews(formattedNews);
      } catch (error) {
        console.error("Gagal ambil data berita:", error);
        setNews(null);
      } finally {
        setLoading(false);
      }
    };

    loadNews();
  }, [slug]);

  if (loading) {
    return (
      <div style={{ padding: "100px 20px", textAlign: "center" }}>
        <img src="/icons/data.gif" alt="Loading..." style={{ width: "60px" }} />
        <p>Muat berita...</p>
      </div>
    );
  }

  if (!news) {
    return (
      <div style={{ padding: "100px 20px", textAlign: "center" }}>
        <h3>Berita tidak ditemukan</h3>
        <button
          onClick={() => router.back()}
          style={{
            backgroundColor: "#0d6efd",
            color: "white",
            border: "none",
            padding: "0.5rem 1rem",
            borderRadius: "5px",
            cursor: "pointer",
            marginTop: "1rem",
          }}
        >
          ← Kembali
        </button>
      </div>
    );
  }

  return (
    <>
      <Navigation />
      <section
        style={{
          backgroundColor: "white",
          padding: "100px 20px",
          color: "#222",
          minHeight: "100vh",
          boxSizing: "border-box",
        }}
      >
        <div
          style={{
            maxWidth: "900px",
            margin: "0 auto",
            padding: "0 20px",
          }}
        >
          {/* Tombol Kembali */}
          <button
            onClick={() => router.back()}
            style={{
              marginBottom: "1rem",
              backgroundColor: "#0d6efd",
              color: "white",
              border: "none",
              padding: "0.5rem 1rem",
              borderRadius: "5px",
              cursor: "pointer",
              fontWeight: "bold",
            }}
          >
            ← Kembali
          </button>

          {/* Gambar Berita */}
          <div
            style={{
              width: "100%",
              height: "400px",
              borderRadius: "10px",
              overflow: "hidden",
              backgroundColor: "#ddd",
              marginBottom: "1.5rem",
              boxShadow: "0 4px 12px rgba(0,0,0,0.1)",
              position: "relative",
            }}
          >
            {!imageLoaded && !imageError && (
              <div
                style={{
                  position: "absolute",
                  top: "50%",
                  left: "50%",
                  transform: "translate(-50%, -50%)",
                  width: "50%",
                  height: "50%",
                  zIndex: 1,
                }}
              >
                <img
                  src="/icons/data.gif"
                  alt="Loading"
                  style={{ width: "100%", height: "100%", display: "block" }}
                />
              </div>
            )}

            {imageError && (
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
                  fontSize: "1rem",
                  fontWeight: "bold",
                  zIndex: 2,
                }}
              >
                Gambar tidak tersedia
              </div>
            )}

            <img
              src={news.image}
              alt={news.title}
              style={{
                width: "100%",
                height: "100%",
                objectFit: "cover",
                opacity: imageLoaded ? 1 : 0,
                transition: "opacity 0.3s ease",
              }}
              loading="lazy"
              onLoad={() => setImageLoaded(true)}
              onError={() => setImageError(true)}
            />
          </div>

          {/* Kategori */}
          <div
            style={{
              fontSize: "0.9rem",
              color: "#0d6efd",
              fontWeight: "bold",
              marginBottom: "0.5rem",
            }}
          >
            {news.category}
          </div>

          {/* Judul */}
          <h1
            style={{
              fontSize: "2rem",
              fontWeight: "bold",
              marginBottom: "1rem",
              color: "black",
            }}
          >
            {news.title}
          </h1>

          {/* Tanggal */}
          <p
            style={{
              fontSize: "0.9rem",
              color: "#666",
              marginBottom: "1.5rem",
            }}
          >
            {news.date}
          </p>

          {/* Konten */}
          <div
            style={{
              fontSize: "1rem",
              lineHeight: 1.6,
              color: "#333",
              marginBottom: "4rem",
            }}
            dangerouslySetInnerHTML={{ __html: news.content }}
          />

          {/* Berita Terkait */}
          <RelatedNews currentSlug={slug} />
        </div>
      </section>

      <Footer />
    </>
  );
}