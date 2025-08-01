import { useRef, useEffect, useState } from "react";
import styles from "@/app/css/direksi.module.css";
import { fetchData } from "@/lib/api";

function useOnScreen(ref) {
  const [isIntersecting, setIntersecting] = useState(false);
  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        setIntersecting(entry.isIntersecting);
      },
      { threshold: 0.2 }
    );
    const current = ref.current;
    if (current) observer.observe(current);
    return () => {
      if (current) observer.unobserve(current);
    };
  }, [ref]);
  return isIntersecting;
}

export default function DireksiSection() {
  const ref = useRef();
  const isVisible = useOnScreen(ref);
  const [direksi, setDireksi] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadDireksi = async () => {
      try {
        const data = await fetchData("direksi"); // Mengambil dari API Laravel
        const dataWithFlags = data.map((d) => ({
          ...d,
          imageLoaded: false,
          imageError: false,
        }));
        setDireksi(dataWithFlags);
      } catch (error) {
        console.error("Gagal mengambil data direksi:", error);
        setDireksi([]);
      } finally {
        setLoading(false);
      }
    };

    loadDireksi();
  }, []);

  const getDirekturByPosition = (keyword) =>
    direksi.find((d) =>
      d.position.toLowerCase().includes(keyword.toLowerCase())
    );

  const direkturUtama = getDirekturByPosition("utama");
  const direkturKeuangan = getDirekturByPosition("keuangan");
  const direkturOperasional = getDirekturByPosition("operasional");
  const orderedDireksi = [direkturOperasional, direkturUtama, direkturKeuangan].filter(Boolean);

  const renderSkeletonCards = (keyPrefix) => (
    <div className={styles.cardContainer}>
      {[...Array(3)].map((_, idx) => (
        <div key={`${keyPrefix}-${idx}`} className={styles.card}>
          <div className={styles.loaderPlaceholder}>
            <img src="/icons/data.gif" alt="Loading" className={styles.loaderGif} />
          </div>
          <div className={styles.skeletonText}></div>
          <div className={styles.skeletonSubtext}></div>
        </div>
      ))}
    </div>
  );

  return (
    <section ref={ref} className={`${styles.direksiSection} ${isVisible ? styles.fadeIn : ""}`}>
      {loading ? (
        renderSkeletonCards('skeleton')
      ) : orderedDireksi.length === 0 ? (
        renderSkeletonCards('fallback')
      ) : (
        <div className={styles.cardContainer}>
          {orderedDireksi.map((person, idx) => (
            <div key={idx} className={`${styles.card} ${isVisible ? styles.isVisible : ''}`}>
              <div className={styles.circularCard}>
                {!person.imageLoaded && !person.imageError && (
                  <div className={styles.imageLoaderContainer}>
                    <img src="/icons/data.gif" alt="Loading" className={styles.loaderGif} />
                  </div>
                )}
                {!person.imageLoaded && person.imageError && (
                  <div className={styles.errorMessage}>Data tidak bisa dimuat</div>
                )}
                {person.image && !person.imageError && (
                  <img
                    src={person.image}
                    alt={person.name}
                    className={styles.personImage}
                    style={{
                      opacity: person.imageLoaded ? 1 : 0,
                      visibility: person.imageLoaded ? 'visible' : 'hidden',
                    }}
                    loading="lazy"
                    onLoad={() => {
                      setDireksi(prev =>
                        prev.map(d =>
                          d.id === person.id
                            ? { ...d, imageLoaded: true, imageError: false }
                            : d
                        )
                      );
                    }}
                    onError={() => {
                      setDireksi(prev =>
                        prev.map(d =>
                          d.id === person.id
                            ? { ...d, imageError: true, imageLoaded: false }
                            : d
                        )
                      );
                    }}
                  />
                )}
              </div>
              <h4 className="mb-1" style={{ fontWeight: 'bold', color: '#222' }}>
                {person.position}
              </h4>
              <p className="text-muted" style={{ fontSize: '1.1rem' }}>
                {person.name}
              </p>
            </div>
          ))}
        </div>
      )}
    </section>
  );
}