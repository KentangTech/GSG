"use client";

import { useEffect, useState, useRef } from "react";
import Link from "next/link";
import style from "@/app/css/AboutPage.module.css";
import { fetchData } from "@/lib/api";

function truncateDescHalf(text, percentage = 0.5) {
  const cleanText = text.replace(/<[^>]*>/g, '').trim();
  const length = cleanText.length;
  if (length <= 100) return cleanText;
  const targetLength = Math.floor(length * percentage);
  let cutIndex = targetLength;
  const nextPeriod = cleanText.indexOf('. ', cutIndex);
  if (nextPeriod !== -1) {
    cutIndex = nextPeriod + 1;
  } else {
    const nextSpace = cleanText.lastIndexOf(' ', targetLength);
    cutIndex = nextSpace !== -1 ? nextSpace : targetLength;
  }
  return cutIndex < length ? cleanText.slice(0, cutIndex).trim() + '...' : cleanText;
}

function generateSlug(title) {
  return title.toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "");
}

export default function BusinessSection({
  openModal,
  closeModal,
  modalImage,
  scale,
  position,
  isDragging,
  handleWheel,
  handleMouseDown,
  handleMouseMove,
  handleMouseUp,
}) {
  const [bisnisData, setBisnisData] = useState([]);
  const [isVisible, setIsVisible] = useState({});
  const isResetting = useRef(false);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [centeredCards, setCenteredCards] = useState(new Set());
  const totalItems = bisnisData.length;
  const visibleItemsDesktop = 4;
  const visibleItemsMobile = 2;
  const gap = 10;
  const extendedData = [...bisnisData, ...bisnisData, ...bisnisData];
  const sliderRef = useRef(null);
  const cardRefs = useRef([]);

  useEffect(() => {
    const loadBisnis = async () => {
      try {
        const data = await fetchData("api/bisnis");
        setBisnisData(data);
      } catch (error) {
        console.error("Gagal ambil data bisnis:", error);
        setBisnisData([]);
      }
    };

    loadBisnis();
  }, []);

  // Auto-slide
  useEffect(() => {
    if (bisnisData.length === 0) return;

    const interval = setInterval(() => {
      if (!isResetting.current) {
        goToNext();
      }
    }, 3000);
    return () => clearInterval(interval);
  }, [bisnisData.length]);

  // Reset loop halus
  useEffect(() => {
    if (!sliderRef.current || bisnisData.length === 0) return;

    const itemWidth = window.innerWidth >= 992 ? 100 / visibleItemsDesktop : 100 / visibleItemsMobile;
    const totalGap = gap * (window.innerWidth >= 992 ? visibleItemsDesktop : visibleItemsMobile);
    const resetThreshold = totalItems * 2;

    if (currentIndex >= resetThreshold) {
      isResetting.current = true;
      sliderRef.current.style.transition = "transform 0.8s ease";
      sliderRef.current.style.transform = `translateX(-${(itemWidth + totalGap / 100) * currentIndex}%)`;
      setTimeout(() => {
        sliderRef.current.style.transition = "none";
        setCurrentIndex(totalItems);
        sliderRef.current.style.transform = `translateX(-${(itemWidth + totalGap / 100) * totalItems}%)`;
        isResetting.current = false;
      }, 800);
    } else {
      sliderRef.current.style.transition = "transform 0.8s ease";
      sliderRef.current.style.transform = `translateX(-${(itemWidth + totalGap / 100) * currentIndex}%)`;
    }
  }, [currentIndex, totalItems]);

  // Intersection Observer (judul)
  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible((prev) => ({ ...prev, "9": true }));
          observer.unobserve(entry.target);
        }
      },
      { threshold: 0.1 }
    );

    const element = document.querySelector('[data-idx="9"]');
    if (element) observer.observe(element);

    return () => {
      if (element) observer.unobserve(element);
    };
  }, []);

  // Touch Handlers
  const touchStartX = useRef(0);
  const touchEndX = useRef(0);

  const handleTouchStart = (e) => {
    touchStartX.current = e.touches[0].clientX;
  };

  const handleTouchMove = (e) => {
    touchEndX.current = e.touches[0].clientX;
  };

  const handleTouchEnd = () => {
    if (!touchStartX.current || !touchEndX.current) return;
    const diff = touchStartX.current - touchEndX.current;
    if (diff > 30) {
      goToNext();
    } else if (diff < -30) {
      goToPrev();
    }
    touchStartX.current = null;
    touchEndX.current = null;
  };

  const goToPrev = () => setCurrentIndex((prev) => Math.max(prev - 1, 0));
  const goToNext = () => setCurrentIndex((prev) => prev + 1);

  // Center Detection (Mobile)
  useEffect(() => {
    if (window.innerWidth >= 992 || bisnisData.length === 0) return;

    const observer = new IntersectionObserver(
      (entries) => {
        const newCentered = new Set();
        entries.forEach((entry) => {
          const rect = entry.boundingClientRect;
          const parentRect = entry.target.parentElement.getBoundingClientRect();
          const centerX = parentRect.left + parentRect.width / 2;
          const elementCenterX = rect.left + rect.width / 2;
          const distance = Math.abs(centerX - elementCenterX);
          const threshold = rect.width * 0.3;
          if (distance < threshold) {
            newCentered.add(entry.target.dataset.index);
          }
        });
        setCenteredCards(newCentered);
      },
      { root: null, rootMargin: "0px", threshold: 0.5 }
    );

    cardRefs.current.forEach((card) => {
      if (card) observer.observe(card);
    });

    return () => {
      cardRefs.current.forEach((card) => {
        if (card) observer.unobserve(card);
      });
    };
  }, [currentIndex, bisnisData.length]);

  if (bisnisData.length === 0) {
    return (
      <section className="py-10 bg-gradient-to-br from-white via-blue-50 to-indigo-50">
        <div className={style.sectionContainer}>
          <div className="text-center">
            <p>Loading bisnis...</p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="py-10 bg-gradient-to-br from-white via-blue-50 to-indigo-50">
      <div className={style.sectionContainer}>
        {/* Judul */}
        <div
          data-idx="9"
          className={`${style.titleFadeIn} ${isVisible["9"] ? style.visible : ""} text-center mb-12`}
        >
          <h2 className="text-3xl md:text-4xl fw-bold text-dark">
            Bisnis & <span style={{ color: "var(--primary-blue)" }}>Usaha</span>
          </h2>
          <p className="text-muted mt-3 fs-5 max-w-2xl mx-auto">
            Diversifikasi bisnis PT Graha Sarana Gresik untuk mendukung pertumbuhan berkelanjutan.
          </p>
        </div>

        <div className="position-relative overflow-hidden">
          {/* Desktop Slider */}
          <div className="d-none d-lg-block">
            <div
              className="d-flex"
              ref={sliderRef}
              style={{ transition: "transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)" }}
            >
              {extendedData.map((item, index) => (
                <div
                  key={index}
                  className="px-3 flex-shrink-0"
                  style={{ width: `${100 / visibleItemsDesktop}%` }}
                >
                  <div
                    className={`${style.cardContainer} card shadow-sm rounded-4 h-100`}
                    onClick={() => openModal(item.image)}
                  >
                    <div className={style.cardImage}>
                      <img src={item.image} alt={item.title} />
                    </div>
                    <div className="card-body p-4">
                      <h6 className={`${style.cardTitle} fs-5`}>{item.title}</h6>
                      {item.tag && <span className={style.cardTag}>{item.tag}</span>}
                      <p className={style.cardDesc}>
                        {truncateDescHalf(item.desc, 0.5)}
                      </p>
                      <Link href={`/about/${generateSlug(item.title)}`}>
                        <button
                          className={style.cardButton}
                          onClick={(e) => e.stopPropagation()}
                        >
                          Lihat Detail
                        </button>
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Mobile Slider */}
          <div className="d-lg-none">
            <div className="d-flex justify-content-end mb-3 gap-2">
              <button onClick={goToPrev} className={style.navButton} aria-label="Sebelumnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                </svg>
              </button>
              <button onClick={goToNext} className={style.navButton} aria-label="Berikutnya">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                </svg>
              </button>
            </div>

            <div
              className={style.sliderContainer}
              style={{
                transform: `translateX(-${currentIndex * (100 / visibleItemsMobile)}%)`,
                gap: `${gap}px`,
                scrollSnapType: "x mandatory",
              }}
              onTouchStart={handleTouchStart}
              onTouchMove={handleTouchMove}
              onTouchEnd={handleTouchEnd}
            >
              {extendedData.map((item, index) => (
                <div
                  key={index}
                  ref={(el) => (cardRefs.current[index] = el)}
                  data-index={index}
                  className="flex-shrink-0"
                  style={{
                    width: `calc(${100 / visibleItemsMobile}% - ${gap / 2}px)`,
                    scrollSnapAlign: "start",
                    transition: "transform 0.4s ease, box-shadow 0.4s ease",
                    transform: centeredCards.has(index.toString()) ? "translateY(-8px)" : "translateY(0)",
                    boxShadow: centeredCards.has(index.toString())
                      ? "0 12px 24px rgba(0, 0, 0, 0.15)"
                      : "0 4px 12px rgba(0, 0, 0, 0.08)",
                  }}
                >
                  <div
                    className={`${style.cardContainer} card shadow-sm rounded-4 h-100`}
                    onClick={() => openModal(item.image)}
                  >
                    <div className={`${style.cardImage} ${style.cardImageMobile}`}>
                      <img src={item.image} alt={item.title} />
                    </div>
                    <div className="card-body p-3">
                      <h6 className={`${style.cardTitle} fs-6`}>{item.title}</h6>
                      {item.tag && <span className={style.cardTag}>{item.tag}</span>}
                      <p className={style.cardDesc}>
                        {truncateDescHalf(item.desc, 0.5)}
                      </p>
                      <Link href={`/about/${generateSlug(item.title)}`}>
                        <button
                          className={style.cardButton}
                          onClick={(e) => e.stopPropagation()}
                        >
                          Lihat Detail
                        </button>
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>

            {/* Indikator Slide */}
            <div className="d-flex justify-content-center mt-4">
              {Array.from({ length: Math.ceil(extendedData.length / visibleItemsMobile) }).map((_, i) => (
                <div
                  key={i}
                  className={`${style.indicator} ${i === Math.floor(currentIndex / visibleItemsMobile) ? style.active : ""}`}
                />
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Modal Gambar */}
      {modalImage && (
        <div className={style.modalOverlay} onClick={closeModal}>
          <div
            className={style.modalImageContainer}
            onClick={(e) => e.stopPropagation()}
          >
            <button type="button" className={style.closeButton} onClick={closeModal} aria-label="Tutup">
              ×
            </button>
            <img
              src={modalImage}
              alt="Zoom"
              className={style.modalImage}
              style={{
                transform: `scale(${scale}) translate(${position.x}px, ${position.y}px)`,
                cursor: scale > 1 ? (isDragging ? "grabbing" : "grab") : "zoom-out",
              }}
              onWheel={handleWheel}
              onMouseDown={handleMouseDown}
              onMouseMove={handleMouseMove}
              onMouseUp={handleMouseUp}
            />
            <div className={style.zoomInfo}>{Math.round(scale * 100)}%</div>
          </div>
        </div>
      )}
    </section>
  );
}