"use client";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Navigation from "../components/NavigationALT";
import Footer from "../components/footer";
import styles from "@/app/css/NewsPage.module.css";
import LoadingSpinner from "@/app/components/LoadingSpinner";
import "../globals.css";

const CustomCategoryDropdown = ({
  categories,
  selectedCategory,
  onSelectCategory,
}) => {
  const [isOpen, setIsOpen] = useState(false);
  const handleToggle = () => {
    setIsOpen(!isOpen);
  };
  const handleSelect = (category) => {
    onSelectCategory(category);
    setIsOpen(false);
  };

  return (
    <div className={styles.customDropdown}>
      <div className={styles.dropdownHeader} onClick={handleToggle}>
        <span>{selectedCategory}</span>
        <span className={`${styles.arrow} ${isOpen ? styles.arrowOpen : ""}`}>
          ▼
        </span>
      </div>
      <div className={styles.dropdownListContainer}>
        <ul className={`${styles.dropdownList} ${isOpen ? styles.open : ""}`}>
          {categories.map((category) => (
            <li
              key={category}
              className={`${styles.dropdownItem} ${
                selectedCategory === category ? styles.dropdownItemSelected : ""
              }`}
              onClick={() => handleSelect(category)}
            >
              {category}
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
};

export default function NewsPage() {
  const router = useRouter();
  const [newsList, setNewsList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("All");
  const newsPerPage = 6;

  const dummyNews = [
    {
      id: 1,
      title: "Revolutionary AI Technology Transforms Healthcare Industry",
      excerpt:
        "New artificial intelligence breakthrough promises to revolutionize patient care and medical diagnosis accuracy with unprecedented precision.",
      image: "/image/visi-&-misi.jpg",
      category: "Technology",
      date: "March 15, 2024",
      readTime: "5 min read",
    },
    {
      id: 2,
      title: "Sustainable Energy Solutions Gain Global Momentum",
      excerpt:
        "Countries worldwide adopt innovative renewable energy projects to combat climate change effectively while boosting economic growth.",
      image: "/image/news2.jpg",
      category: "Environment",
      date: "March 12, 2024",
      readTime: "7 min read",
    },
    {
      id: 3,
      title: "Future of Remote Work: Trends and Predictions 2024",
      excerpt:
        "Experts analyze the evolving landscape of remote work and its lasting impact on global business operations and company culture.",
      image: "/image/news3.jpg",
      category: "Business",
      date: "March 10, 2024",
      readTime: "4 min read",
    },
    {
      id: 4,
      title: "Breakthrough in Quantum Computing Achieved",
      excerpt:
        "Scientists successfully demonstrate quantum supremacy with new processor architecture that solves complex problems in minutes.",
      image: "/image/news4.jpg",
      category: "Technology",
      date: "March 8, 2024",
      readTime: "6 min read",
    },
    {
      id: 5,
      title: "Global Markets Respond to Economic Policy Changes",
      excerpt:
        "Financial experts discuss implications of new monetary policies on international trade and investment opportunities.",
      image: "/image/news5.jpg",
      category: "Business",
      date: "March 5, 2024",
      readTime: "5 min read",
    },
    {
      id: 6,
      title: "Innovative Education Platforms Transform Learning",
      excerpt:
        "Digital learning solutions revolutionize classroom experiences with personalized AI-driven educational content delivery.",
      image: "/image/news6.jpg",
      category: "Education",
      date: "March 3, 2024",
      readTime: "4 min read",
    },
    {
      id: 7,
      title: "Global Climate Summit Reaches Historic Agreement",
      excerpt:
        "World leaders unite on ambitious new climate targets with binding commitments for carbon neutrality by 2050.",
      image: "/image/news7.jpg",
      category: "Environment",
      date: "February 28, 2024",
      readTime: "8 min read",
    },
    {
      id: 8,
      title: "Revolution in Biotechnology: Gene Therapy Breakthrough",
      excerpt:
        "Scientists achieve major milestone in treating genetic disorders with new gene editing techniques showing 95% success rate.",
      image: "/image/news8.jpg",
      category: "Technology",
      date: "February 25, 2024",
      readTime: "6 min read",
    },
  ];

  const categories = [
    "All",
    ...new Set(dummyNews.map((news) => news.category)),
  ];

  const filteredNews = dummyNews.filter((news) => {
    const matchesCategory =
      selectedCategory === "All" || news.category === selectedCategory;
    const matchesSearch =
      news.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      news.excerpt.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  const totalPages = Math.ceil(filteredNews.length / newsPerPage);
  const indexOfLastNews = currentPage * newsPerPage;
  const indexOfFirstNews = indexOfLastNews - newsPerPage;
  const currentNews = filteredNews.slice(indexOfFirstNews, indexOfLastNews);

  useEffect(() => {
    const timer = setTimeout(() => {
      const newsWithFlags = dummyNews.map((news) => ({
        ...news,
        imageLoaded: false,
        imageError: false,
      }));
      setNewsList(newsWithFlags);
      setLoading(false);
    }, 1500);

    return () => clearTimeout(timer);
  }, []);

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

  const handleImageLoad = (id) => {
    setNewsList((prev) =>
      prev.map((news) =>
        news.id === id ? { ...news, imageLoaded: true } : news
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

  if (loading) {
    return <LoadingSpinner />;
  }

  if (error) {
    return (
      <section className={styles.errorSection}>
        <h3 className={styles.errorTitle}>Terjadi kesalahan</h3>
        <p className={styles.errorMessage}>{error}</p>
        <button
          className={styles.retryButton}
          onClick={() => window.location.reload()}
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
        <section className={styles.newsSection}>
          <div className={styles.container}>
            <div className={styles.header}>
              <h1 className={styles.modernTitle}>
                <span className={styles.titleHighlight}>Latest</span> News &
                Insights
              </h1>
              <p className={styles.subtitle}>
                Stay updated with our latest stories and industry insights
              </p>
            </div>
            <div className={styles.searchContainer}>
              <input
                type="text"
                placeholder="Search news..."
                value={searchQuery}
                onChange={(e) => {
                  setSearchQuery(e.target.value);
                  setCurrentPage(1);
                }}
                className={styles.searchInput}
              />
            </div>
            <div className={styles.categoryDropdown}>
              <CustomCategoryDropdown
                categories={categories}
                selectedCategory={selectedCategory}
                onSelectCategory={(category) => {
                  setSelectedCategory(category);
                  setCurrentPage(1);
                }}
              />
            </div>
            <div className={styles.contentLayout}>
              <div className={styles.sidebar}>
                <h3 className={styles.sidebarTitle}>Categories</h3>
                <div className={styles.categoryList}>
                  {categories.map((category) => (
                    <button
                      key={category}
                      onClick={() => {
                        setSelectedCategory(category);
                        setCurrentPage(1);
                      }}
                      className={`${styles.categoryButton} ${
                        selectedCategory === category
                          ? styles.activeCategory
                          : ""
                      }`}
                    >
                      {category}
                    </button>
                  ))}
                </div>
              </div>
              <div className={styles.newsContent}>
                <div className={styles.newsGrid}>
                  {currentNews.map((news) => {
                    const slug = news.title.toLowerCase().replace(/\s+/g, "-");
                    return (
                      <article
                        key={news.id}
                        className={styles.newsCard}
                        onClick={() => router.push(`/news/${slug}`)}
                      >
                        <div className={styles.imageContainer}>
                          {!news.imageLoaded && !news.imageError && (
                            <img
                              src="/icons/data.gif"
                              alt="Loading..."
                              className={styles.loaderImage}
                            />
                          )}
                          {!news.imageLoaded && news.imageError && (
                            <div className={styles.errorMessageImage}>
                              Image not available
                            </div>
                          )}
                          {news.image && !news.imageError && (
                            <img
                              src={news.image}
                              alt={news.title}
                              onLoad={() => handleImageLoad(news.id)}
                              onError={() => handleImageError(news.id)}
                              className={`${styles.newsImage} ${
                                news.imageLoaded ? styles.imageLoaded : ""
                              }`}
                            />
                          )}
                        </div>
                        <div className={styles.cardContent}>
                          <span
                            className={`${styles.categoryBadge} ${
                              styles[news.category.toLowerCase()] || ""
                            }`}
                          >
                            {news.category}
                          </span>
                          <h3 className={styles.newsTitle}>{news.title}</h3>
                          <div className={styles.metaInfo}>
                            <span className={styles.date}>{news.date}</span>
                            <span className={styles.readTime}>
                              {news.readTime}
                            </span>
                          </div>
                          <p className={styles.excerpt}>{news.excerpt}</p>
                          <button className={styles.readMoreButton}>
                            Read More →
                          </button>
                        </div>
                      </article>
                    );
                  })}
                </div>
                {totalPages > 1 && (
                  <div className={styles.paginationContainer}>
                    <button
                      onClick={goToPrevPage}
                      disabled={currentPage === 1}
                      className={`${styles.paginationButton} ${
                        currentPage === 1 ? styles.disabledButton : ""
                      }`}
                    >
                      ← Previous
                    </button>
                    <div className={styles.paginationInfo}>
                      <span>
                        Page {currentPage} of {totalPages}
                      </span>
                      <span>•</span>
                      <span>{filteredNews.length} articles found</span>
                    </div>
                    <button
                      onClick={goToNextPage}
                      disabled={currentPage === totalPages}
                      className={`${styles.paginationButton} ${
                        currentPage === totalPages ? styles.disabledButton : ""
                      }`}
                    >
                      Next →
                    </button>
                  </div>
                )}
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </>
  );
}
