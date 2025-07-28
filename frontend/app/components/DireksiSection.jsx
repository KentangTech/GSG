import { useRef, useEffect, useState } from "react";
import styles from "@/app/css/direksi.module.css";

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
    fetch("http://localhost:8000/api/direksi")
      .then((res) => {
        if (!res.ok) throw new Error("HTTP status " + res.status);
        return res.json();
      })
      .then((data) => {
        const dataWithFlags = data.map((d) => ({
          ...d,
          imageLoaded: false,
          imageError: false,
        }));
        setDireksi(dataWithFlags);
      })
      .catch((err) => {
        console.error("Gagal mengambil data direksi:", err);
      })
      .finally(() => {
        setLoading(false);
      });
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
        <div
          key={`${keyPrefix}-${idx}`}
          className={styles.card}
        >
          <div className={styles.loaderPlaceholder}>
            <img
              src="/icons/data.gif"
              alt="Loading"
              className={styles.loaderGif}
            />
          </div>
          <div className={styles.skeletonText}></div>
          <div className={styles.skeletonSubtext}></div>
        </div>
      ))}
    </div>
  );

  return (
    <section
      ref={ref}
      className={`${styles.direksiSection} ${isVisible ? styles.fadeIn : ""}`}
    >
      {loading ? (
        renderSkeletonCards('skeleton')
      ) : orderedDireksi.length === 0 ? (
        renderSkeletonCards('fallback')
      ) : (
        <div className={styles.cardContainer}>
          {orderedDireksi.map((person, idx) => (
            <div
              key={idx}
              className={`${styles.card} ${isVisible ? styles.isVisible : ''}`}
            >
              <div
                className={styles.circularCard}
              >
                {!person.imageLoaded && !person.imageError && (
                  <div className={styles.imageLoaderContainer}>
                    <img
                      src="/icons/data.gif"
                      alt="Loading"
                      className={styles.loaderGif}
                    />
                  </div>
                )}
                {!person.imageLoaded && person.imageError && (
                  <div className={styles.errorMessage}>
                    Data tidak bisa dimuat
                  </div>
                )}
                {person.image && !person.imageError && (
                  <img
                    src={person.image}
                    alt={person.name}
                    className={styles.personImage}
                    style={{
                      opacity: person.imageLoaded ? 1 : 0,
                      visibility: person.imageLoaded ? "visible" : "hidden",
                    }}
                    loading="lazy"
                    onLoad={() => {
                      const updatedDireksi = [...direksi];
                      const updatedPerson = updatedDireksi.find(
                        (d) => d.id === person.id
                      );
                      if (updatedPerson) {
                        updatedPerson.imageLoaded = true;
                        updatedPerson.imageError = false;
                        setDireksi([...updatedDireksi]);
                      }
                    }}
                    onError={(e) => {
                      const updatedDireksi = [...direksi];
                      const updatedPerson = updatedDireksi.find(
                        (d) => d.id === person.id
                      );
                      if (updatedPerson) {
                        updatedPerson.imageError = true;
                        setDireksi([...updatedDireksi]);
                      }
                    }}
                  />
                )}
              </div>
              <h4 className="mb-1" style={{ fontWeight: "bold", color: "#222" }}>
                {person.position}
              </h4>
              <p className="text-muted" style={{ fontSize: "1.1rem" }}>
                {person.name}
              </p>
            </div>
          ))}
        </div>
      )}
    </section>
  );
}