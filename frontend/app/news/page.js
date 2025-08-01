"use client";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Navigation from "../components/NavigationALT";
import Footer from "../components/footer";
import styles from "@/app/css/NewsPage.module.css";
import LoadingSpinner from "@/app/components/LoadingSpinner";
import { fetchData } from "@/lib/api";
import "../globals.css";

const CustomCategoryDropdown = ({
  categories,
  selectedCategory,
  onSelectCategory,
}) => {
  const [isOpen, setIsOpen] = useState(false);
  const handleToggle = () => setIsOpen(!isOpen);
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

  // Ambil data dari API Laravel
  useEffect(() => {
    const loadNews = async () => {
      try {
        setLoading(true);
        const data = await fetchData("news"); // endpoint: /api/news
        const formatted = data.map((item) => ({
          id: item.id,
          title: item.title,
          excerpt:
            item.excerpt ||
            item.desc ||
            item.content?.substring(0, 120) + "..." ||
            "No description available.",
          image: item.image?.startsWith("http")
            ? item.image
            : `${process.env.NEXT_PUBLIC_LARAVEL_API}/${item.image}`,
          category: item.category || "Uncategorized",
          date: new Date(item.created_at).toLocaleDateString("id-ID", {
            year: "numeric",
            month: "long",
            day: "numeric",
          }),
          readTime: item.read_time || "5 min read",
          imageLoaded: false,
          imageError: false,
        }));
        setNewsList(formatted);
      } catch (err) {
        setError("Gagal memuat berita. Silakan coba lagi nanti.");
        console.error("Fetch news error:", err);
      } finally {
        setLoading(false);
      }
    };

    loadNews();
  }, []);

  // Ekstrak kategori unik
  const categories = ["All", ...new Set(newsList.map((n) => n.category))];

  // Filter berita berdasarkan pencarian dan kategori
  const filteredNews = newsList.filter((news) => {
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

  const goToNextPage = () => {
    if (currentPage < totalPages) setCurrentPage(currentPage + 1);
  };

  const goToPrevPage = () => {
    if (currentPage > 1) setCurrentPage(currentPage - 1);
  };

  const handleImageLoad = (id) => {
    setNewsList((prev) =>
      prev.map((n) => (n.id === id ? { ...n, imageLoaded: true } : n))
    );
  };

  const handleImageError = (id) => {
    setNewsList((prev) =>
      prev.map((n) => (n.id === id ? { ...n, imageError: true } : n))
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
                  {currentNews.length === 0 ? (
                    <p className={styles.noResults}>
                      Tidak ada berita yang ditemukan.
                    </p>
                  ) : (
                    currentNews.map((news) => {
                      const slug = news.title
                        .toLowerCase()
                        .replace(/\s+/g, "-")
                        .replace(/[^\w\-]/g, "");
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
                    })
                  )}
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
