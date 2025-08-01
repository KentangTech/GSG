"use client";
import { useState, useEffect } from "react";
import Image from "next/image";
import { FaInstagram, FaTwitter, FaFacebook, FaLinkedin } from "react-icons/fa";
import style from "../css/medsos.module.css";
import { fetchData } from "@/lib/api";

const SocialMediaCard = () => {
  const [socialMediaData, setSocialMediaData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadSocialMedia = async () => {
      try {
        const data = await fetchData("social-media"); // endpoint: /api/social-media
        const formatted = data.map(item => ({
          id: item.id,
          name: item.name,
          username: item.username,
          image: item.image?.startsWith("http")
            ? item.image
            : `${process.env.NEXT_PUBLIC_LARAVEL_API}/${item.image}`,
          platforms: item.platforms?.map(p => ({
            name: p.name,
            url: p.url,
            icon: getPlatformIcon(p.name),
          })) || [],
        }));
        setSocialMediaData(formatted);
      } catch (error) {
        console.error("Gagal ambil data media sosial:", error);
        setSocialMediaData([]);
      } finally {
        setLoading(false);
      }
    };

    loadSocialMedia();
  }, []);

  const getPlatformIcon = (name) => {
    const n = name.toLowerCase();
    if (n.includes("instagram") || n === "ig") return <FaInstagram />;
    if (n.includes("twitter") || n === "x") return <FaTwitter />;
    if (n.includes("facebook") || n === "fb") return <FaFacebook />;
    if (n.includes("linkedin") || n === "in") return <FaLinkedin />;
    return <FaInstagram />; // default
  };

  if (loading) {
    return (
      <div className={style.container}>
        <p>Loading media sosial...</p>
      </div>
    );
  }

  return (
    <div className={style.container} id="social-media-section">
      <div className={style.content}>
        <h1 className={style.title}>Our Social Media</h1>
        <div className={style.cardGrid}>
          {socialMediaData.length > 0 ? (
            socialMediaData.map((user) => (
              <a
                key={user.id}
                href={user.platforms[0]?.url || "#"}
                target="_blank"
                rel="noopener noreferrer"
                className={style.cardLink}
              >
                <div className={style.card}>
                  <div className={style.imageContainer}>
                    <div className={style.imageWrapper}>
                      <div className={style.imageBingkai}>
                        <Image
                          src={user.image}
                          alt={user.name}
                          fill
                          style={{ objectFit: "contain" }}
                          sizes="(max-width: 300px) 100vw"
                        />
                      </div>
                    </div>
                  </div>
                  <div className={style.cardContent}>
                    <h2 className={style.name}>{user.name}</h2>
                    <p className={style.username}>{user.username}</p>
                    <div className={style.iconContainer}>
                      {user.platforms.map((platform, index) => (
                        <button
                          key={index}
                          onClick={(e) => {
                            e.stopPropagation();
                            window.open(platform.url, "_blank");
                          }}
                          className={style.iconButton}
                          aria-label={platform.name}
                        >
                          {platform.icon}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </a>
            ))
          ) : (
            <p>Tidak ada data media sosial.</p>
          )}
        </div>
      </div>
    </div>
  );
};

export default SocialMediaCard;