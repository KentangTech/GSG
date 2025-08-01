import React from "react";
import Footer from "@/app/components/footer";
import { notFound } from "next/navigation";
import style from "@/app/css/AboutSlug.module.css";
import { fetchData } from "@/lib/api";

export default async function BisnisDetailPage({ params }) {
  const { slug } = params;

  let item = null;

  try {
    item = await fetchData(`bisnis/${slug}`);
  } catch (error) {
    console.error("Error fetching bisnis detail:", error);
  }

  if (!item) {
    notFound();
  }

  return (
    <main className={style.mainContainer}>
      <div className={style.pageContainer}>
        {/* Back Button */}
        <div className={style.backButtonContainer}>
          <a href="/about" className={style.backButton}>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              fill="currentColor"
              viewBox="0 0 24 24"
              className={style.arrowIcon}
            >
              <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
            </svg>
            <span>Kembali ke About</span>
          </a>
        </div>

        {/* Detail Content */}
        <div className={style.detailWrapper}>
          <div className={style.imageWrapper}>
            <img src={item.image} alt={item.title} loading="lazy" />
          </div>
          <div className={style.textWrapper}>
            {item.tag && <span className={style.tag}>{item.tag}</span>}
            <h1 className={style.title}>{item.title}</h1>
            <p className={style.description}>{item.desc}</p>
          </div>
        </div>
      </div>

      <footer className={style.footer}>
        <Footer />
      </footer>
    </main>
  );
}