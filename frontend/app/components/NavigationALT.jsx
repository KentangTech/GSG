// components/Navigation.jsx
import React, { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import styles from '@/app/css/NavigationALT.module.css';

const Navigation = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const pathname = usePathname();

  const toggleMenu = () => {
    setIsMenuOpen(!isMenuOpen);
  };

  const closeMenu = () => {
    setIsMenuOpen(false);
  };

  const handleNavClick = (e, targetId) => {
    closeMenu();
    if (pathname === '/') {
      e.preventDefault();
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        const offsetTop = targetElement.offsetTop - 100;
        window.scrollTo({
          top: offsetTop,
          behavior: 'smooth'
        });
      }
    }
  };

  return (
    <nav className={styles.navbar}>
      <div className={styles.navContainer}>
        <Link href="/" className={styles.navLogo} onClick={closeMenu}>
          <img src="/image/GSG-kecil.png" alt="Logo Perusahaan" className={styles.logoImg} />
        </Link>

        <button 
          className={`${styles.hamburger} ${isMenuOpen ? styles.active : ''}`} 
          onClick={toggleMenu}
          aria-label="Toggle navigation menu"
          aria-expanded={isMenuOpen}
        >
          <span className={styles.bar}></span>
          <span className={styles.bar}></span>
          <span className={styles.bar}></span>
        </button>

        <ul className={`${styles.navMenu} ${isMenuOpen ? styles.navMenuActive : ''}`}>
          <li className={styles.navItem}>
            <Link href="/" className={styles.navLink} onClick={closeMenu}>
              Home
            </Link>
          </li>
          <li className={styles.navItem}>
            <Link
              href="/#about"
              className={styles.navLink}
              onClick={(e) => handleNavClick(e, 'about')}
            >
              About Us
            </Link>
          </li>
          <li className={styles.navItem}>
            <Link
              href="/#kebijakan"
              className={styles.navLink}
              onClick={(e) => handleNavClick(e, 'kebijakan')}
            >
              Kebijakan
            </Link>
          </li>
          <li className={styles.navItem}>
            <Link
              href="/#news"
              className={styles.navLink}
              onClick={(e) => handleNavClick(e, 'news')}
            >
              News
            </Link>
          </li>
          <li className={styles.navItem}>
            <Link
              href="/#medsos"
              className={styles.navLink}
              onClick={(e) => handleNavClick(e, 'medsos')}
            >
              Media Sosial
            </Link>
          </li>
        </ul>
      </div>
    </nav>
  );
};

export default Navigation;