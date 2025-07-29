import React from "react";
import Head from "next/head";
import Link from 'next/link';
import styles from "@/app/css/NewsSection.module.css";

const NewsSection = () => {
  const newsData = [
    {
      id: 1,
      title: "Revolutionary AI Technology Transforms Healthcare Industry",
      excerpt:
        "New artificial intelligence breakthrough promises to revolutionize patient care and medical diagnosis accuracy with unprecedented precision.",
      date: "March 15, 2024",
      category: "Technology",
      image:
        "https://images.unsplash.com/photo-1677442135722-5f11e06a4e6d?w=400&h=250&fit=crop",
      readTime: "5 min read",
    },
    {
      id: 2,
      title: "Sustainable Energy Solutions Gain Global Momentum",
      excerpt:
        "Countries worldwide adopt innovative renewable energy projects to combat climate change effectively while boosting economic growth.",
      date: "March 12, 2024",
      category: "Environment",
      image:
        "https://images.unsplash.com/photo-1497435334941-8c8934d3451f?w=400&h=250&fit=crop",
      readTime: "7 min read",
    },
    {
      id: 3,
      title: "Future of Remote Work: Trends and Predictions 2024",
      excerpt:
        "Experts analyze the evolving landscape of remote work and its lasting impact on global business operations and company culture.",
      date: "March 10, 2024",
      category: "Business",
      image:
        "https://images.unsplash.com/photo-1552664730-d307ca884978?w=400&h=250&fit=crop",
      readTime: "4 min read",
    },
    {
      id: 4,
      title: "Breakthrough in Quantum Computing Achieved",
      excerpt:
        "Scientists successfully demonstrate quantum supremacy with new processor architecture that solves complex problems in minutes.",
      date: "March 8, 2024",
      category: "Technology",
      image:
        "https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=400&h=250&fit=crop",
      readTime: "6 min read",
    },
    {
      id: 5,
      title: "Global Markets Respond to Economic Policy Changes",
      excerpt:
        "Financial experts discuss implications of new monetary policies on international trade and investment opportunities.",
      date: "March 5, 2024",
      category: "Business",
      image:
        "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400&h=250&fit=crop",
      readTime: "5 min read",
    },
    {
      id: 6,
      title: "Innovative Education Platforms Transform Learning",
      excerpt:
        "Digital learning solutions revolutionize classroom experiences with personalized AI-driven educational content delivery.",
      date: "March 3, 2024",
      category: "Education",
      image:
        "https://images.unsplash.com/photo-1523580494863-6f3031224c94?w=400&h=250&fit=crop",
      readTime: "4 min read",
    },
  ];

  // State untuk hover effects
  const [hoveredCard, setHoveredCard] = React.useState(null);
  const [hoveredButton, setHoveredButton] = React.useState(false);
  const [hoveredReadMore, setHoveredReadMore] = React.useState(null);

  const NewsCard = ({ news }) => (
    <article
      className={`${styles.newsCard} ${
        hoveredCard === news.id ? styles.newsCardHover : ""
      }`}
      onMouseEnter={() => setHoveredCard(news.id)}
      onMouseLeave={() => setHoveredCard(null)}
    >
      <div className={styles.imageContainer}>
        <img
          src={news.image}
          alt={news.title}
          className={`${styles.newsImage} ${
            hoveredCard === news.id ? styles.imageHover : ""
          }`}
        />
        <span
          className={`${styles.categoryBadge} ${
            styles[news.category.toLowerCase()] || ""
          }`}
        >
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
          className={`${styles.readMore} ${
            hoveredReadMore === news.id ? styles.readMoreHover : ""
          }`}
          onMouseEnter={() => setHoveredReadMore(news.id)}
          onMouseLeave={() => setHoveredReadMore(null)}
        >
          Read More →
        </button>
      </div>
    </article>
  );

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
            {newsData.map((news) => (
              <NewsCard key={news.id} news={news} />
            ))}
          </div>

          <div className={styles.viewAllContainer} href="/news">
            <Link href="/news" passHref>
              <button
                className={`${styles.viewAllButton} ${
                  hoveredButton ? styles.viewAllButtonHover : ""
                }`}
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
