"use client";
import { useEffect, useState } from "react";
import Link from "next/link";
import ScrollToTopButton from "../components/ScrollToTopButton";
import Footer from "../components/footer";
import Navigation from "../components/NavigationALT";
import "../globals.css";

function shadeColor(color, percent) {
  let R = parseInt(color.substring(1, 3), 16);
  let G = parseInt(color.substring(3, 5), 16);
  let B = parseInt(color.substring(5, 7), 16);

  R = Math.min(255, Math.max(0, R + (R * percent) / 100));
  G = Math.min(255, Math.max(0, G + (G * percent) / 100));
  B = Math.min(255, Math.max(0, B + (B * percent) / 100));

  const RR = Math.round(R).toString(16).padStart(2, "0");
  const GG = Math.round(G).toString(16).padStart(2, "0");
  const BB = Math.round(B).toString(16).padStart(2, "0");

  return `#${RR}${GG}${BB}`;
}

function TypingText({ prefix, animatedText, index, isVisible, primaryBlue }) {
  const [displayText, setDisplayText] = useState("");
  const [isDeleting, setIsDeleting] = useState(false);
  const [loopNum, setLoopNum] = useState(0);
  const [isStarting, setIsStarting] = useState(true);

  useEffect(() => {
    if (!isVisible || !animatedText) {
      if (animatedText) {
        setDisplayText(animatedText);
      }
      return;
    }

    const typeSpeed = isDeleting ? 70 : 150;
    const pauseTime = 2000;
    const startDelay = 300;

    let isCancelled = false;

    const handleTyping = () => {
      if (isCancelled) return;

      setDisplayText((prevText) => {
        if (isCancelled) return prevText;

        if (isDeleting) {
          return animatedText.substring(0, prevText.length - 1);
        } else {
          return animatedText.substring(0, prevText.length + 1);
        }
      });

      if (!isDeleting && displayText === animatedText) {
        if (!isCancelled) {
          setTimeout(() => {
            if (!isCancelled) setIsDeleting(true);
          }, pauseTime);
        }
        return;
      } else if (isDeleting && displayText === "") {
        if (!isCancelled) {
          setIsDeleting(false);
          setLoopNum((prev) => prev + 1);
        }
        return;
      }
    };

    let timerId;

    if (isStarting) {
      timerId = setTimeout(() => {
        if (isCancelled) return;
        setIsStarting(false);

        const typeRecursive = () => {
          if (isCancelled || !isVisible) return;

          const typingTimer = setTimeout(() => {
            handleTyping();
            typeRecursive();
          }, typeSpeed);

          return typingTimer;
        };

        const currentTimerId = typeRecursive();
        timerId = currentTimerId;
      }, startDelay);
    } else {
      const typeRecursive = () => {
        if (isCancelled || !isVisible) return;

        const typingTimer = setTimeout(() => {
          handleTyping();
          typeRecursive();
        }, typeSpeed);

        return typingTimer;
      };

      const currentTimerId = typeRecursive();
      timerId = currentTimerId;
    }

    return () => {
      isCancelled = true;
      if (timerId) {
        clearTimeout(timerId);
      }
    };
  }, [displayText, isDeleting, loopNum, isStarting, isVisible, animatedText]);

  return (
    <h5
      className="fw-bold"
      style={{
        color: primaryBlue,
        minHeight: "2rem",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      {prefix}
      {displayText}
      <span
        style={{
          display: isStarting ? "none" : "inline-block",
          width: "3px",
          height: "1.2em",
          backgroundColor: primaryBlue,
          marginLeft: "2px",
          animation: "smoothBlink 1.5s infinite",
          opacity: 1,
        }}
      />
    </h5>
  );
}

export default function AboutPage() {
  const [isVisible, setIsVisible] = useState({});
  const [currentIndex, setCurrentIndex] = useState(0);
  const [modalImage, setModalImage] = useState(null);
  const [scale, setScale] = useState(1);
  const [position, setPosition] = useState({ x: 0, y: 0 });
  const [isDragging, setIsDragging] = useState(false);
  const [startPos, setStartPos] = useState({ x: 0, y: 0 });
  const primaryBlue = "#1976d2";
  const primaryBlueDark = shadeColor(primaryBlue, -15);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const idx = entry.target.dataset.idx;
            setIsVisible((prev) => ({ ...prev, [idx]: true }));
            observer.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );
    const elements = document.querySelectorAll("[data-idx]");
    elements.forEach((el) => observer.observe(el));
    return () => {
      elements.forEach((el) => observer.unobserve(el));
      observer.disconnect();
    };
  }, []);

  useEffect(() => {
    setIsVisible((prev) => ({ ...prev, backBtn: true }));
  }, []);

  useEffect(() => {
    const totalItems = bisnisData.length;
    const visibleItems = window.innerWidth <= 768 ? 2 : 4;
    const maxIndex = Math.max(totalItems - visibleItems, 0);
    const interval = setInterval(() => {
      setCurrentIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
    }, 6000);
    return () => clearInterval(interval);
  }, []);

  const openModal = (imageSrc) => {
    setModalImage(imageSrc);
    setScale(1);
    setPosition({ x: 0, y: 0 });
    setIsDragging(false);
    document.body.style.overflow = "hidden";
  };

  const closeModal = () => {
    setModalImage(null);
    document.body.style.overflow = "";
  };

  const handleWheel = (e) => {
    e.preventDefault();
    let newScale = scale - e.deltaY * 0.001;
    newScale = Math.min(Math.max(newScale, 1), 3);
    setScale(newScale);
    if (newScale === 1) setPosition({ x: 0, y: 0 });
  };

  const onTouchStart = (e) => {
    if (e.touches.length === 2) {
      const [a, b] = e.touches;
      setStartPos({
        dist: Math.hypot(a.clientX - b.clientX, a.clientY - b.clientY),
      });
    } else if (e.touches.length === 1 && scale > 1) {
      setIsDragging(true);
      const touch = e.touches[0];
      setStartPos({
        x: touch.clientX - position.x,
        y: touch.clientY - position.y,
      });
    }
  };

  const onTouchMove = (e) => {
    if (e.touches.length === 2) {
      const [a, b] = e.touches;
      const currentDist = Math.hypot(
        a.clientX - b.clientX,
        a.clientY - b.clientY
      );
      const newScale = scale * (currentDist / startPos.dist);
      setScale(Math.min(Math.max(newScale, 1), 3));
      setStartPos({ dist: currentDist });
      if (newScale <= 1) setPosition({ x: 0, y: 0 });
    } else if (e.touches.length === 1 && isDragging) {
      const touch = e.touches[0];
      setPosition({
        x: touch.clientX - startPos.x,
        y: touch.clientY - startPos.y,
      });
    }
  };

  const onTouchEnd = () => {
    setIsDragging(false);
  };

  const handleMouseDown = (e) => {
    if (scale > 1) {
      setIsDragging(true);
      setStartPos({
        x: e.clientX - position.x,
        y: e.clientY - position.y,
      });
    }
  };

  const handleMouseMove = (e) => {
    if (isDragging) {
      setPosition({
        x: e.clientX - startPos.x,
        y: e.clientY - startPos.y,
      });
    }
  };

  const handleMouseUp = () => {
    setIsDragging(false);
  };

  const goToPrev = () => {
    const totalItems = bisnisData.length;
    const visibleItems = window.innerWidth <= 768 ? 2 : 4;
    const maxIndex = Math.max(totalItems - visibleItems, 0);
    setCurrentIndex((prev) => (prev <= 0 ? maxIndex : prev - 1));
  };

  const goToNext = () => {
    const totalItems = bisnisData.length;
    const visibleItems = window.innerWidth <= 768 ? 2 : 4;
    const maxIndex = Math.max(totalItems - visibleItems, 0);
    setCurrentIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
  };

  const bisnisData = [
    {
      title: "Perdagangan Umum",
      desc: "Menjalankan usaha di bidang perdagangan maupun impor antara lain : Spare part, Bahan baku Industri atau Chemical dan Pupuk, Mekanikal Rotaring dan Non Rotaring, Instrumen & Pipa Fitting.",
      image: "/bisnis/Perdagangan-Barang.jpg",
      tag: "DIV.PUBP",
    },
    {
      title: "Mustikarasa Cafe dan Resto",
      desc: "Menjalankan usaha dalam bidang Cafe & Resto yang dikelola secara profesional yang berarsitektur tradisional Jawa dengan sentuhan-sentuhan modern.",
      image: "/bisnis/Cafe-Dan-Resto.jpg",
      tag: "DIV.PERDAGANGAN JASA",
    },
    {
      title: "Perumahan Bella Casa",
      desc: "Menjalankan usaha di bidang perumahan yang mengedepankan lingkungan asri dan nyaman bagi penghuni.",
      image: "/bisnis/Perumahan-Bella-Casa.jpg",
      tag: "DIV.PROPERTY & PERUMAHAN",
    },
    {
      title: "Pergudangan",
      desc: "Menjalankan usaha di bidang jasa pergudangan yang aman, strategis, dan dilengkapi sistem keamanan terpadu.",
      image: "/bisnis/Pergudangan.jpg",
      tag: "DIV.PROPERTY & PERUMAHAN",
    },
    {
      title: "Property Perkantoran",
      desc: "Menjalankna usaha di bidang jasa sewa ruang dan manajemen pengelolaan gedung serta pembangunan infrastruktur perkantoran.",
      image: "/bisnis/Property-Perkantoran.jpg",
      tag: "DIV.PROPERTY & PERUMAHAN",
    },
    {
      title: "Jasa sewa alat berat & Kendaraan",
      desc: "Menjalankan usaha di bidang persewaan angkutan kendaraan seperti bis, mobil hingga alat berat.",
      image: "/bisnis/Jasa-Sewa-Kendaraan.jpg",
      tag: "DIV.PERDAGANGAN JASA",
    },
    {
      title: "Jasa Konstruksi",
      desc: "Menjalankan usaha di bidang konstruksi bangunan dan infrastruktur dengan tenaga ahli dan peralatan modern.",
      image: "/bisnis/Jasa-Konstruksi.jpg",
      tag: "DIV.PERDAGANGAN JASA",
    },
    {
      title: "Tour & Travel",
      desc: "Menyediakan layanan tour wisata dan travel umroh/haji dengan pelayanan prima dan harga kompetitif.",
      image: "/bisnis/Tour-Dan-Travel.jpg",
      tag: "GRESIK GRAHA WISATA",
    },
  ];

  const totalItems = bisnisData.length;
  const visibleItemsDesktop = 4;
  const visibleItemsMobile = 2;

  return (
    <><Navigation />
    <div className="min-h-screen bg-white">
      <div
        className="w-100 position-relative overflow-hidden"
        style={{
          background: "linear-gradient(135deg, #0d47a1, #1976d2, #42a5f5)",
          marginTop: "-1rem",
        }}
      >
        <div
          className="w-100 position-relative overflow-hidden"
          style={{
            background: "linear-gradient(135deg, #0d47a1, #1976d2, #42a5f5)",
            marginTop: "-1rem",
          }}
        >
          <div
            className="container"
            style={{
              paddingLeft: "1.5rem",
              paddingRight: "1.5rem",
              paddingTop: "2.5rem",
              paddingBottom: "3.5rem",
              textAlign: "center",
            }}
          >
            <h1
              className="display-4 fw-bold mb-4"
              data-idx="0"
              style={{
                color: "white",
                textShadow: "0 2px 10px rgba(0,0,0,0.25)",
                opacity: isVisible["0"] ? 1 : 0,
                transform: isVisible["0"]
                  ? "translateY(0)"
                  : "translateY(-20px)",
                transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1)",
              }}
            >
              Tentang Kami
            </h1>
            <p
              className="lead mb-0 fs-4"
              data-idx="1"
              style={{
                color: "rgba(255, 255, 255, 0.95)",
                maxWidth: "800px",
                margin: "0 auto",
                lineHeight: "1.6",
                opacity: isVisible["1"] ? 1 : 0,
                transform: isVisible["1"]
                  ? "translateY(0)"
                  : "translateY(20px)",
                transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.2s",
              }}
            >
              PT Graha Sarana Gresik – Komitmen kami terhadap mutu, lingkungan,
              dan keamanan pangan dalam setiap layanan.
            </p>
          </div>
        </div>
        <div
          style={{
            position: "absolute",
            top: "50%",
            right: "10%",
            width: "80px",
            height: "80px",
            borderRadius: "50%",
            backgroundColor: "rgba(255, 255, 255, 0.1)",
            filter: "blur(20px)",
            transform: "translateY(-50%)",
            pointerEvents: "none",
          }}
        ></div>
        <div
          style={{
            position: "absolute",
            bottom: "10%",
            left: "5%",
            width: "60px",
            height: "60px",
            borderRadius: "50%",
            backgroundColor: "rgba(255, 255, 255, 0.08)",
            filter: "blur(15px)",
            pointerEvents: "none",
          }}
        ></div>
      </div>

      {/* Visi & Misi */}
      <section className="py-5 bg-light">
        <div className="container px-4">
          <div className="row align-items-center g-5">
            <div className="col-lg-6 order-2 col-md-12">
              <div
                className="rounded-4 overflow-hidden shadow-lg cursor-pointer"
                data-idx="2"
                onClick={() => openModal("/image/visi-&-misi.jpg")}
                style={{
                  height: "300px",
                  backgroundColor: "#f8f9fa",
                  cursor: "zoom-in",
                  transition: "transform 0.3s ease, box-shadow 0.3s ease",
                }}
                onMouseEnter={(e) => {
                  e.target.style.transform = "scale(1.02)";
                  e.target.style.boxShadow = "0 16px 40px rgba(0,0,0,0.15)";
                } }
                onMouseLeave={(e) => {
                  e.target.style.transform = "none";
                  e.target.style.boxShadow = "0 12px 32px rgba(0,0,0,0.1)";
                } }
              >
                <img
                  src="/image/visi-&-misi.jpg"
                  alt="PT Graha Sarana Gresik"
                  className="w-100 h-100"
                  style={{ objectFit: "cover" }} />
              </div>
            </div>
            <div className="col-lg-6 order-1 col-md-12">
              <div
                data-idx="3"
                style={{
                  opacity: isVisible["3"] ? 1 : 0,
                  transform: isVisible["3"]
                    ? "translateX(0)"
                    : "translateX(-20px)",
                  transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.4s",
                }}
              >
                <h2 className="fw-bold" style={{ color: primaryBlue }}>
                  Visi
                </h2>
                <p className="text-dark mt-3 fs-5">
                  Menjadi perusahaan yang sehat dan berkembang di bidang
                  properti, angkutan, perdagangan, pergudangan, perkantoran, dan
                  jasa bongkar muat.
                </p>
              </div>
              <div
                className="mt-5"
                data-idx="4"
                style={{
                  opacity: isVisible["4"] ? 1 : 0,
                  transform: isVisible["4"]
                    ? "translateX(0)"
                    : "translateX(-20px)",
                  transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.5s",
                }}
              >
                <h2 className="fw-bold" style={{ color: primaryBlue }}>
                  Misi
                </h2>
                <ul className="list-unstyled mt-3 text-dark fs-5">
                  {[
                    "Berorientasi pada pelayanan yang prima.",
                    "Menjunjung tinggi nilai-nilai integritas, profesionalisme, dan tanggung jawab.",
                    "Meningkatkan kualitas sumber daya manusia secara berkelanjutan.",
                    "Memberikan nilai tambah kepada pelanggan, pemegang saham, dan masyarakat.",
                  ].map((item, idx) => (
                    <li key={idx} className="mb-3 d-flex align-items-start">
                      <span
                        className="me-3 fs-4"
                        style={{ color: primaryBlue }}
                      >
                        ✓
                      </span>
                      <span>{item}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Nilai-Nilai Graha dengan Animasi Mengetik (hanya bagian setelah "Graha: ") */}
      <section className="py-5 bg-white">
        <div className="container px-4">
          <div
            className="text-center mb-5"
            data-idx="5"
            style={{
              opacity: isVisible["5"] ? 1 : 0,
              transform: isVisible["5"] ? "translateY(0)" : "translateY(20px)",
              transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.6s",
            }}
          >
            <h2 className="fw-bold text-dark" style={{ color: primaryBlue }}>
              Nilai-Nilai Graha
            </h2>
            <p className="text-muted fs-5">
              Panduan utama dalam pengembangan bisnis dan budaya kerja kami.
            </p>
          </div>
          <div className="row g-4 justify-content-center">
            {/* Data untuk Nilai-Nilai Graha dengan prefix dan animatedText terpisah */}
            {[
              {
                prefix: "Graha: ",
                animatedText: "Inovatif",
                desc: "Selalu mencari cara baru dan lebih baik dalam menjalankan bisnis dan memberikan layanan.",
              },
              {
                prefix: "Graha: ",
                animatedText: "Kompeten",
                desc: "Memiliki tim profesional yang ahli di bidangnya dan terus mengembangkan kapabilitas.",
              },
              {
                prefix: "Graha: ",
                animatedText: "Berintegritas",
                desc: "Menjunjung tinggi kejujuran, tanggung jawab, dan etika dalam setiap keputusan.",
              },
            ].map((item, index) => (
              <div className="col-md-4" key={index}>
                <div
                  className="card border-0 shadow-sm h-100 rounded-3 text-center p-4"
                  data-idx={`${6 + index}`}
                  style={{
                    opacity: isVisible[`${6 + index}`] ? 1 : 0,
                    transform: isVisible[`${6 + index}`]
                      ? "translateY(0)"
                      : "translateY(30px)",
                    transition: `all 1s cubic-bezier(0.4, 0, 0.2, 1) ${0.7 + index * 0.1}s`,
                  }}
                >
                  <div className="fs-1 mb-3">🚀</div>
                  {/* Komponen TypingText dengan prefix dan animatedText terpisah */}
                  <TypingText
                    prefix={item.prefix}
                    animatedText={item.animatedText}
                    index={index}
                    isVisible={isVisible[`${6 + index}`]}
                    primaryBlue={primaryBlue} />
                  <p className="text-muted">{item.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Bisnis & Usaha */}
      <section className="py-5 bg-light position-relative">
        <div className="container px-4">
          <div
            className="text-center mb-5"
            data-idx="9"
            style={{
              opacity: isVisible["9"] ? 1 : 0,
              transform: isVisible["9"] ? "translateY(0)" : "translateY(20px)",
              transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.8s",
            }}
          >
            <h2 className="fw-bold text-dark">
              Bisnis & <span style={{ color: primaryBlue }}>Usaha</span>
            </h2>
            <p className="text-muted fs-5">
              Diversifikasi bisnis PT Graha Sarana Gresik untuk mendukung
              pertumbuhan berkelanjutan.
            </p>
          </div>
          <div className="position-relative overflow-hidden">
            <div className="d-none d-lg-flex align-items-center">
              <button
                onClick={goToPrev}
                className="btn rounded-circle p-2 position-absolute border"
                style={{
                  left: "-50px",
                  top: "50%",
                  transform: "translateY(-50%)",
                  borderColor: primaryBlue,
                  color: primaryBlue,
                  backgroundColor: "white",
                  transition: "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
                onMouseEnter={(e) => {
                  e.target.style.backgroundColor = primaryBlue;
                  e.target.style.color = "white";
                } }
                onMouseLeave={(e) => {
                  e.target.style.backgroundColor = "white";
                  e.target.style.color = primaryBlue;
                } }
                aria-label="Sebelumnya"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
                </svg>
              </button>
              <div
                className="d-flex w-100"
                style={{
                  transform: `translateX(-${(currentIndex /
                      Math.max(totalItems - visibleItemsDesktop, 1)) *
                    100}%)`,
                  transition: "transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
              >
                {bisnisData.map((item, index) => (
                  <div
                    key={index}
                    className="px-2 flex-shrink-0"
                    style={{ width: `${100 / visibleItemsDesktop}%` }}
                  >
                    <div className="text-center">
                      <div
                        className="rounded-3 overflow-hidden shadow-sm border mb-3 cursor-pointer"
                        style={{
                          height: "180px",
                          backgroundColor: "#f8f9fa",
                          cursor: "zoom-in",
                          transition: "transform 0.3s ease, box-shadow 0.3s ease",
                        }}
                        onMouseEnter={(e) => {
                          e.target.style.transform = "scale(1.03)";
                          e.target.style.boxShadow =
                            "0 12px 24px rgba(0,0,0,0.15)";
                        } }
                        onMouseLeave={(e) => {
                          e.target.style.transform = "none";
                          e.target.style.boxShadow =
                            "0 6px 12px rgba(0,0,0,0.1)";
                        } }
                        onClick={() => openModal(item.image)}
                      >
                        <img
                          src={item.image}
                          alt={item.title}
                          className="w-100 h-100"
                          style={{ objectFit: "cover" }} />
                      </div>
                      <h6 className="fw-bold" style={{ color: primaryBlue }}>
                        {item.title}
                      </h6>
                      {item.tag && (
                        <span
                          className="badge text-white mb-1"
                          style={{
                            fontSize: "0.8rem",
                            padding: "0.5em 0.8em",
                            backgroundColor: primaryBlue,
                          }}
                        >
                          {item.tag}
                        </span>
                      )}
                      <p className="text-muted small">{item.desc}</p>
                    </div>
                  </div>
                ))}
              </div>
              <button
                onClick={goToNext}
                className="btn rounded-circle p-2 position-absolute border"
                style={{
                  right: "-50px",
                  top: "50%",
                  transform: "translateY(-50%)",
                  borderColor: primaryBlue,
                  color: primaryBlue,
                  backgroundColor: "white",
                  transition: "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
                onMouseEnter={(e) => {
                  e.target.style.backgroundColor = primaryBlue;
                  e.target.style.color = "white";
                } }
                onMouseLeave={(e) => {
                  e.target.style.backgroundColor = "white";
                  e.target.style.color = primaryBlue;
                } }
                aria-label="Berikutnya"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                </svg>
              </button>
            </div>
            <div className="d-lg-none">
              <div
                className="d-flex"
                style={{
                  transform: `translateX(-${(currentIndex /
                      Math.max(totalItems - visibleItemsMobile, 1)) *
                    100}%)`,
                  transition: "transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
              >
                {bisnisData.map((item, index) => (
                  <div
                    key={index}
                    className="px-2 flex-shrink-0"
                    style={{ width: `${100 / visibleItemsMobile}%` }}
                  >
                    <div className="text-center">
                      <div
                        className="rounded-3 overflow-hidden shadow-sm border mb-3 cursor-pointer"
                        style={{
                          height: "160px",
                          backgroundColor: "#f8f9fa",
                          cursor: "zoom-in",
                          transition: "transform 0.3s ease, box-shadow 0.3s ease",
                        }}
                        onMouseEnter={(e) => {
                          e.target.style.transform = "scale(1.02)";
                          e.target.style.boxShadow =
                            "0 10px 20px rgba(0,0,0,0.12)";
                        } }
                        onMouseLeave={(e) => {
                          e.target.style.transform = "none";
                          e.target.style.boxShadow =
                            "0 6px 12px rgba(0,0,0,0.1)";
                        } }
                        onClick={() => openModal(item.image)}
                      >
                        <img
                          src={item.image}
                          alt={item.title}
                          className="w-100 h-100"
                          style={{ objectFit: "cover" }} />
                      </div>
                      <h6 className="fw-bold" style={{ color: primaryBlue }}>
                        {item.title}
                      </h6>
                      {item.tag && (
                        <span
                          className="badge text-white mb-1"
                          style={{
                            fontSize: "0.8rem",
                            padding: "0.5em 0.8em",
                            backgroundColor: primaryBlue,
                          }}
                        >
                          {item.tag}
                        </span>
                      )}
                      <p className="text-muted small">{item.desc}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <div className="d-lg-none text-center mt-4">
              <button
                onClick={goToPrev}
                className="btn mx-2 border"
                style={{
                  borderColor: primaryBlue,
                  color: primaryBlue,
                  backgroundColor: "white",
                  transition: "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
                onMouseEnter={(e) => {
                  e.target.style.backgroundColor = primaryBlue;
                  e.target.style.color = "white";
                } }
                onMouseLeave={(e) => {
                  e.target.style.backgroundColor = "white";
                  e.target.style.color = primaryBlue;
                } }
                aria-label="Sebelumnya"
              >
                Sebelumnya
              </button>
              <button
                onClick={goToNext}
                className="btn mx-2 border"
                style={{
                  borderColor: primaryBlue,
                  color: primaryBlue,
                  backgroundColor: "white",
                  transition: "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)",
                }}
                onMouseEnter={(e) => {
                  e.target.style.backgroundColor = primaryBlue;
                  e.target.style.color = "white";
                } }
                onMouseLeave={(e) => {
                  e.target.style.backgroundColor = "white";
                  e.target.style.color = primaryBlue;
                } }
                aria-label="Berikutnya"
              >
                Berikutnya
              </button>
            </div>
          </div>
        </div>
      </section>
      <Footer />
      <ScrollToTopButton />
      {modalImage && (
        <div
          className="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
          style={{
            backgroundColor: "rgba(0, 0, 0, 0.9)",
            zIndex: 1060,
            backdropFilter: "blur(8px)",
          }}
          onClick={closeModal}
        >
          <div
            className="position-relative"
            onClick={(e) => e.stopPropagation()}
            onWheel={handleWheel}
            onMouseDown={handleMouseDown}
            onMouseMove={handleMouseMove}
            onMouseUp={handleMouseUp}
            onMouseLeave={handleMouseUp}
            onTouchStart={onTouchStart}
            onTouchMove={onTouchMove}
            onTouchEnd={onTouchEnd}
            style={{
              width: "90vw",
              height: "90vh",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
            }}
          >
            <img
              src={modalImage}
              alt="Zoom Gambar"
              draggable="false"
              style={{
                width: "auto",
                height: "auto",
                maxHeight: "85vh",
                maxWidth: "90vw",
                objectFit: "contain",
                transform: `scale(${scale}) translate(${position.x}px, ${position.y}px)`,
                transition: isDragging ? "none" : "transform 0.1s ease-out",
                cursor: scale > 1 ? (isDragging ? "grabbing" : "grab") : "zoom-out",
                position: "relative",
                zIndex: 1,
              }} />
            <div
              className="position-absolute bottom-0 end-0 bg-black text-white px-2 py-1 rounded"
              style={{ fontSize: "0.8rem", zIndex: 2 }}
            >
              {Math.round(scale * 100)}%
            </div>
          </div>
          <button
            type="button"
            className="position-absolute top-0 end-0 m-3 btn-close btn-close-white"
            style={{ fontSize: "2rem", zIndex: 3 }}
            aria-label="Close"
            onClick={closeModal}
          ></button>
        </div>
      )}
      <style jsx global>{`
        @keyframes smoothBlink {
          0%,
          100% {
            opacity: 1;
          }
          50% {
            opacity: 0;
          }
        }
      `}</style>
    </div></>
  );
}
