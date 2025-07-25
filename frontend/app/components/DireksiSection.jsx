import { useRef, useEffect, useState } from "react";

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

  return (
    <section
      ref={ref}
      className={`direksi-section ${isVisible ? "fade-in" : ""}`}
      style={{
        padding: "4rem 1rem 5rem",
        backgroundColor: "transparent",
        textAlign: "center",
        transition: "all 1s ease",
      }}
    >
      {loading ? (
        <div className="d-flex flex-wrap justify-content-center gap-5">
          {[...Array(3)].map((_, idx) => (
            <div
              key={`skeleton-${idx}`}
              className="text-center"
              style={{
                width: "250px",
                opacity: 0.7,
              }}
            >
              <div
                style={{
                  width: "250px",
                  height: "250px",
                  borderRadius: "50%",
                  backgroundColor: "#e0e0e0",
                  marginBottom: "15px",
                  position: "relative",
                  overflow: "hidden",
                }}
              >
                <img
                  src="/icons/Lazy-Loading.gif"
                  alt="Loading"
                  style={{
                    width: "50%",
                    height: "50%",
                    margin: "auto",
                    display: "block",
                    position: "absolute",
                    top: "50%",
                    left: "50%",
                    transform: "translate(-50%, -50%)",
                  }}
                />
              </div>
              <div
                style={{
                  width: "80%",
                  height: "20px",
                  backgroundColor: "#e0e0e0",
                  margin: "10px auto",
                  borderRadius: "4px",
                }}
              ></div>
              <div
                style={{
                  width: "60%",
                  height: "16px",
                  backgroundColor: "#e0e0e0",
                  margin: "5px auto",
                  borderRadius: "4px",
                }}
              ></div>
            </div>
          ))}
        </div>
      ) : orderedDireksi.length === 0 ? (
        <div className="d-flex flex-wrap justify-content-center gap-5">
          {[...Array(3)].map((_, idx) => (
            <div
              key={`fallback-${idx}`}
              className="text-center"
              style={{
                width: "250px",
                opacity: 0.7,
              }}
            >
              <div
                style={{
                  width: "250px",
                  height: "250px",
                  borderRadius: "50%",
                  backgroundColor: "#e0e0e0",
                  marginBottom: "15px",
                  position: "relative",
                  overflow: "hidden",
                }}
              >
                <img
                  src="/icons/Lazy-Loading.gif"
                  alt="Loading"
                  style={{
                    width: "50%",
                    height: "50%",
                    margin: "auto",
                    display: "block",
                    position: "absolute",
                    top: "50%",
                    left: "50%",
                    transform: "translate(-50%, -50%)",
                  }}
                />
              </div>
              <div
                style={{
                  width: "80%",
                  height: "20px",
                  backgroundColor: "#e0e0e0",
                  margin: "10px auto",
                  borderRadius: "4px",
                }}
              ></div>
              <div
                style={{
                  width: "60%",
                  height: "16px",
                  backgroundColor: "#e0e0e0",
                  margin: "5px auto",
                  borderRadius: "4px",
                }}
              ></div>
            </div>
          ))}
        </div>
      ) : (
        <div className="d-flex flex-wrap justify-content-center gap-5">
          {orderedDireksi.map((person, idx) => (
            <div
              key={idx}
              className="text-center"
              style={{
                width: "250px",
                transform: isVisible ? "scale(1)" : "scale(0.9)",
                opacity: isVisible ? 1 : 0.7,
                transition: "all 0.6s ease",
              }}
            >
              {/* Circular Card */}
              <div
                style={{
                  width: "250px",
                  height: "250px",
                  borderRadius: "50%",
                  overflow: "hidden",
                  border: "4px solid #4e73df",
                  boxShadow: "0 4px 10px rgba(0,0,0,0.2)",
                  marginBottom: "15px",
                  position: "relative",
                }}
                onMouseEnter={(e) =>
                  (e.currentTarget.style.transform = "scale(1.1)")
                }
                onMouseLeave={(e) =>
                  (e.currentTarget.style.transform = "scale(1)")
                }
              >
                {/* Tampilkan SVG Loader selama gambar belum dimuat */}
                {!person.imageLoaded && !person.imageError && (
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

                {/* Tampilkan pesan error jika timeout */}
                {!person.imageLoaded && person.imageError && (
                  <div
                    style={{
                      width: "100%",
                      height: "100%",
                      backgroundColor: "#f8d7da",
                      color: "#721c24",
                      display: "flex",
                      justifyContent: "center",
                      alignItems: "center",
                      fontSize: "1rem",
                      fontWeight: "bold",
                      textAlign: "center",
                      textTransform: "uppercase",
                      padding: "10px",
                    }}
                  >
                    Data tidak bisa dimuat
                  </div>
                )}

                {/* Gambar dengan onLoad dan onError */}
                {person.image && !person.imageError && (
                  <img
                    src={person.image}
                    alt={person.name}
                    style={{
                      width: "100%",
                      height: "100%",
                      objectFit: "cover",
                      transition: "opacity 0.3s ease",
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

              {/* Nama dan Jabatan */}
              <h4
                className="mb-1"
                style={{ fontWeight: "bold", color: "#222" }}
              >
                {person.position}
              </h4>
              <p className="text-muted" style={{ fontSize: "1.1rem" }}>
                {person.name}
              </p>
            </div>
          ))}
        </div>
      )}

      <style jsx>{`
        .direksi-section {
          opacity: 0;
          transform: translateY(30px);
        }

        .direksi-section.fade-in {
          opacity: 1;
          transform: translateY(0);
        }
      `}</style>
    </section>
  );
}