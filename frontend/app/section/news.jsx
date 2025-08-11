import React, { useState, useEffect } from "react";
import Head from "next/head";
import Link from 'next/link';
import styles from "@/app/css/NewsSection.module.css";
import { fetchData } from "@/lib/api";

const NewsSection = () => {
  const [newsData, setNewsData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [hoveredCard, setHoveredCard] = useState(null);
  const [hoveredButton, setHoveredButton] = useState(false);
  const [hoveredReadMore, setHoveredReadMore] = useState(null);

  // Ambil data dari API Laravel
  useEffect(() => {
    const loadNews = async () => {
      try {
        const data = await fetchData("api/news");
        const formatted = data.map(item => ({
          id: item.id,
          title: item.title,
          excerpt: item.excerpt || item.desc || item.content?.substring(0, 120) + "..." || "No description available.",
          date: new Date(item.created_at).toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
            day: "numeric",
          }),
          category: item.category || "Uncategorized",
          readTime: item.read_time || "5 min read",
          image: item.image?.startsWith("http")
            ? item.image
            : `${process.env.NEXT_PUBLIC_LARAVEL_API}/${item.image}`,
        }));
        setNewsData(formatted);
      } catch (error) {
        console.error("Gagal ambil berita:", error);
        setNewsData([]); // tetap render, tapi tanpa data
      } finally {
        setLoading(false);
      }
    };

    loadNews();
  }, []);

  const NewsCard = ({ news }) => (
    <article
      className={`${styles.newsCard} ${hoveredCard === news.id ? styles.newsCardHover : ""}`}
      onMouseEnter={() => setHoveredCard(news.id)}
      onMouseLeave={() => setHoveredCard(null)}
    >
      <div className={styles.imageContainer}>
        <img
          src={news.image}
          alt={news.title}
          className={`${styles.newsImage} ${hoveredCard === news.id ? styles.imageHover : ""}`}
          loading="lazy"
        />
        <span className={`${styles.categoryBadge} ${styles[news.category.toLowerCase()] || ""}`}>
          {news.category}
        </span>
      </div>

      <div className={styles.content}>
        <div className={styles.meta}>
          <span className={styles.date}>{news.date}</span>
          <span className={styles.readTime}>{news.readTime}</span>
        </div>

        <h3 className={styles.newsTitle}>{news.title}</h3>
        <p className={styles.excerpt}>{news.excerpt}</p>

        <button
          className={`${styles.readMore} ${hoveredReadMore === news.id ? styles.readMoreHover : ""}`}
          onMouseEnter={() => setHoveredReadMore(news.id)}
          onMouseLeave={() => setHoveredReadMore(null)}
        >
          Read More →
        </button>
      </div>
    </article>
  );

  if (loading) {
    return (
      <section className={styles.newsSection}>
        <div className={styles.container}>
          <p>Loading news...</p>
        </div>
      </section>
    );
  }

  return (
    <div>
      <Head>
        <title>News Section</title>
        <meta name="description" content="Latest News" />
      </Head>

      <section className={styles.newsSection}>
        <div className={styles.container}>
          <div className={styles.header}>
            <h2 className={styles.title}>Latest News</h2>
            <p className={styles.subtitle}>
              Stay updated with our latest stories and insights
            </p>
          </div>

          <div className={styles.newsGrid}>
            {newsData.length > 0 ? (
              newsData.slice(0, 6).map((news) => (
                <NewsCard key={news.id} news={news} />
              ))
            ) : (
              <p>No news available</p>
            )}
          </div>

          <div className={styles.viewAllContainer}>
            <Link href="/news" passHref>
              <button
                className={`${styles.viewAllButton} ${hoveredButton ? styles.viewAllButtonHover : ""}`}
                onMouseEnter={() => setHoveredButton(true)}
                onMouseLeave={() => setHoveredButton(false)}
              >
                View All News
              </button>
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default NewsSection;