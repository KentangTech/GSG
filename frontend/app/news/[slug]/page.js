"use client";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Footer from "@/app/components/footer";
import RelatedNews from "./RelatedNews";
import '@/app/globals.css';

export default function NewsDetail({ params }) {
  const router = useRouter();
  const { slug } = React.use(params) || {};

  const dummyNews = {
    title: "Judul Berita Statis",
    content: `
      <p>
        Ini adalah contoh deskripsi detail berita yang akan ditampilkan secara dinamis nanti.
        Anda bisa menambahkan paragraf, gambar, atau elemen lain di sini.
      </p>
      <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec auctor, 
        nunc eget tincidunt tincidunt, nisl nibh ullamcorper nulla, vitae 
        malesuada justo sem sit amet justo.
      </p>
      <p>
        Sed ut imperdiet massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;
        Curabitur ac justo sit amet metus vehicula congue. Duis vel velit id nisi viverra bibendum.
      </p>
      <p>
        Integer id orci a nulla luctus ultrices. Donec sit amet eros sed ante dapibus eleifend.
        Nulla facilisi. Sed euismod, nisl vel bibendum viverra, justo nulla sagittis augue,
        id lacinia lectus nisi in eros. Curabitur id nulla ac nisi ullamcorper viverra.
      </p>
      <p>
        Phasellus in sapien ac nunc tincidunt placerat. Vivamus euismod, nisl vel bibendum viverra, justo nulla sagittis augue,
        id lacinia lectus nisi in eros. Curabitur id nulla ac nisi ullamcorper viverra.
      </p>
      <p>
        Maecenas at libero vel lectus dapibus, consectetur massa eget, bibendum turpis.
        Integer sed nisi eget nunc volutpat, euismod massa sed, facilisis velit.
      </p>
      <p>
        Pellentesque non lorem eu augue facilisis eleifend. Sed vitae magna sed justo malesuada bibendum.
        Donec non nisi nec arcu blandit, ultricies arcu in, placerat nunc.
      </p>
    `,
    image: "/image/news1.jpg",
    date: "18 Juli 2025",
    category: "Berita Terbaru",
  };

  // State untuk gambar
  const [imageLoaded, setImageLoaded] = useState(false);
  const [imageError, setImageError] = useState(false);

  return (
    <>
      <section
        style={{
          backgroundColor: "white",
          padding: "60px 20px",
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

          {/* Gambar Berita dengan Loader SVG */}
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
            {/* Loader SVG saat gambar dimuat */}
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
                  src="/icons/Lazy-Loading.gif"
                  alt="Loading"
                  style={{
                    width: "100%",
                    height: "100%",
                    display: "block",
                  }}
                />
              </div>
            )}

            {/* Pesan Error jika gambar gagal dimuat */}
            {!imageLoaded && imageError && (
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

            {/* Gambar Berita */}
            <img
              src={dummyNews.image}
              alt={dummyNews.title}
              style={{
                width: "100%",
                height: "100%",
                objectFit: "cover",
                borderRadius: "10px",
                opacity: imageLoaded ? 1 : 0,
                transition: "opacity 0.3s ease",
              }}
              loading="lazy"
              onLoad={() => setImageLoaded(true)}
              onError={() => setImageError(true)}
            />
          </div>

          {/* Kategori Berita */}
          <div
            style={{
              fontSize: "0.9rem",
              color: "#0d6efd",
              fontWeight: "bold",
              marginBottom: "0.5rem",
            }}
          >
            {dummyNews.category}
          </div>

          {/* Judul Berita */}
          <h1
            style={{
              fontSize: "2rem",
              fontWeight: "bold",
              marginBottom: "1rem",
              color: "black",
            }}
          >
            {dummyNews.title}
          </h1>

          {/* Tanggal Berita */}
          <p
            style={{
              fontSize: "0.9rem",
              color: "#666",
              marginBottom: "1.5rem",
            }}
          >
            {dummyNews.date}
          </p>

          {/* Konten Berita */}
          <div
            style={{
              fontSize: "1rem",
              lineHeight: 1.6,
              color: "#333",
              marginBottom: "4rem",
            }}
            dangerouslySetInnerHTML={{ __html: dummyNews.content }}
          />

          {/* Berita Terkait */}
          <RelatedNews />
        </div>
      </section>

      {/* Footer */}
      <Footer />
    </>
  );
}