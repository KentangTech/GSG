import Image from "next/image";
import { FaInstagram, FaTwitter, FaFacebook, FaLinkedin } from "react-icons/fa";
import style from "../css/medsos.module.css";

const socialMediaData = [
  {
    id: 1,
    name: "LinkedIn",
    username: "@linkedin",
    image: "/medsos/BCV-logo.png",

    platforms: [
      {
        name: "instagram",
        url: "https://instagram.com/linkedin",
        icon: <FaInstagram />,
      },
    ],
  },
  {
    id: 2,
    name: "Instagram",
    username: "@instagram",
    image: "/medsos/GGW-logo.png",
    platforms: [
      {
        name: "instagram",
        url: "https://instagram.com/instagram",
        icon: <FaInstagram />,
      },
    ],
  },
  {
    id: 3,
    name: "Twitter",
    username: "@twitter",
    image: "/medsos/medsos.png",
    platforms: [
      {
        name: "instagram",
        url: "https://instagram.com/twitter",
        icon: <FaInstagram />,
      },
    ],
  },
  {
    id: 4,
    name: "Facebook",
    username: "@facebook",
    image: "/medsos/Mustikarasa_logo.png",
    platforms: [
      {
        name: "instagram",
        url: "https://instagram.com/facebook",
        icon: <FaInstagram />,
      },
    ],
  },
];

const SocialMediaCard = () => {
  return (
    <div className={style.container} id="social-media-section">
      <div className={style.content}>
        <h1 className={style.title}>Our Social Media</h1>
        <div className={style.cardGrid}>
          {socialMediaData.map((user) => (
            <a
              key={user.id}
              href={user.instagramUrl}
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
                        layout="fill"
                        objectFit="contain"
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
                      >
                        {platform.icon}
                      </button>
                    ))}
                  </div>
                </div>
              </div>
            </a>
          ))}
        </div>
      </div>
    </div>
  );
};

export default SocialMediaCard;
