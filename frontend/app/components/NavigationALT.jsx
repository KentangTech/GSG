import React, { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';

const Navigation = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const pathname = usePathname();
  const navRef = useRef(null);

  const toggleMenu = () => setIsMenuOpen((prev) => !prev);
  const closeMenu = () => setIsMenuOpen(false);

  const handleNavClick = (e, targetId) => {
    closeMenu();
    if (pathname === '/') {
      e.preventDefault();
      const targetElement = document.getElementById(targetId);
      if (targetElement) {
        const offsetTop = targetElement.offsetTop - 80;
        window.scrollTo({ top: offsetTop, behavior: 'smooth' });
      }
    }
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (navRef.current && !navRef.current.contains(event.target)) {
        closeMenu();
      }
    };

    const handleScroll = () => closeMenu();

    if (isMenuOpen) {
      document.addEventListener('click', handleClickOutside);
      window.addEventListener('scroll', handleScroll);
    }

    return () => {
      document.removeEventListener('click', handleClickOutside);
      window.removeEventListener('scroll', handleScroll);
    };
  }, [isMenuOpen]);

  useEffect(() => {
    closeMenu();
  }, [pathname]);

  useEffect(() => {
    if (isMenuOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'auto';
    }
    return () => {
      document.body.style.overflow = 'auto';
    };
  }, [isMenuOpen]);

  const menuItems = [
    { label: 'Home', action: closeMenu, href: '/' },
    { label: 'About Us', target: 'about' },
    { label: 'Kebijakan', target: 'kebijakan' },
    { label: 'News', target: 'news' },
    { label: 'Media Sosial', target: 'medsos' },
  ];

  return (
    <nav
      ref={navRef}
      style={{
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        backgroundColor: 'rgba(255, 255, 255, 0.98)',
        backdropFilter: 'blur(12px)',
        WebkitBackdropFilter: 'blur(12px)',
        boxShadow: '0 4px 30px rgba(0, 0, 0, 0.08)',
        zIndex: 1050,
        transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)',
        height: '72px',
        borderBottom: '1px solid rgba(30, 64, 175, 0.12)',
        fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
      }}
      aria-label="Main navigation"
    >
      <div
        style={{
          maxWidth: '1280px',
          margin: '0 auto',
          padding: '0 1.5rem',
          height: '100%',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
        }}
      >
        <Link
          href="/"
          onClick={closeMenu}
          style={{
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            color: '#1e40af',
            fontWeight: 700,
            fontSize: '1.3rem',
            textDecoration: 'none',
            transition: 'transform 0.3s ease',
          }}
          onMouseEnter={(e) => (e.target.style.transform = 'scale(1.08)')}
          onMouseLeave={(e) => (e.target.style.transform = 'scale(1)')}
          aria-label="Go to homepage"
        >
          <img
            src="/image/GSG-kecil.png"
            alt="Logo PT Graha Sarana Gresik"
            style={{
              width: '120px',
              height: '120px',
              objectFit: 'contain',
            }}
          />
        </Link>

        <ul
          className="desktop-nav"
          style={{
            display: 'none',
            listStyle: 'none',
            margin: 0,
            padding: 0,
            gap: '24px',
            alignItems: 'center',
          }}
        >
          {menuItems.map((item, index) => (
            <li key={index}>
              <Link
                href={item.href || `/#${item.target}`}
                onClick={(e) => {
                  item.action ? item.action() : handleNavClick(e, item.target);
                }}
                style={{
                  display: 'block',
                  padding: '0.8rem 1.2rem',
                  color: '#4b5563',
                  textDecoration: 'none',
                  fontWeight: 500,
                  fontSize: '1.05rem',
                  borderRadius: '10px',
                  transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
                }}
                onMouseEnter={(e) => {
                  e.target.style.color = '#1e40af';
                  e.target.style.backgroundColor = 'rgba(30, 64, 175, 0.08)';
                  e.target.style.transform = 'translateY(-2px)';
                }}
                onMouseLeave={(e) => {
                  e.target.style.color = '#4b5563';
                  e.target.style.backgroundColor = 'transparent';
                  e.target.style.transform = 'translateY(0)';
                }}
                aria-current={
                  (pathname === '/' && item.target && window.location.hash === `#${item.target}`) ||
                  (item.href && pathname === item.href)
                    ? 'page'
                    : undefined
                }
              >
                {item.label}
              </Link>
            </li>
          ))}
        </ul>

        <button
          type="button"
          onClick={toggleMenu}
          aria-label={isMenuOpen ? 'Close menu' : 'Open menu'}
          aria-expanded={isMenuOpen}
          className="mobile-menu-button"
          style={{
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            width: '34px',
            height: '26px',
            background: 'none',
            border: 'none',
            cursor: 'pointer',
            padding: 0,
            transition: 'all 0.3s ease',
            zIndex: 1051,
          }}
        >
          <span
            style={{
              height: '3px',
              backgroundColor: '#1e40af',
              borderRadius: '2px',
              transform: isMenuOpen ? 'rotate(45deg) translate(5px, 8px)' : 'none',
              transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
            }}
          />
          <span
            style={{
              height: '3px',
              backgroundColor: '#1e40af',
              borderRadius: '2px',
              opacity: isMenuOpen ? 0 : 1,
              transition: 'opacity 0.3s ease',
            }}
          />
          <span
            style={{
              height: '3px',
              backgroundColor: '#1e40af',
              borderRadius: '2px',
              transform: isMenuOpen ? 'rotate(-45deg) translate(5px, -8px)' : 'none',
              transition: 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
            }}
          />
        </button>

        {isMenuOpen && (
          <ul
            className="mobile-dropdown-menu"
            style={{
              position: 'absolute',
              top: '72px',
              right: '1.5rem',
              backgroundColor: 'rgba(255, 255, 255, 0.99)',
              backdropFilter: 'blur(14px)',
              borderRadius: '16px',
              padding: '1.2rem 0',
              boxShadow: '0 16px 40px rgba(0, 0, 0, 0.18)',
              minWidth: '220px',
              border: '1px solid rgba(30, 64, 175, 0.15)',
              listStyle: 'none',
              margin: 0,
              flexDirection: 'column',
              alignItems: 'flex-end',
              gap: '6px',
              zIndex: 1050,
              animation: 'slideDown 0.35s ease forwards',
            }}
            role="menu"
          >
            {menuItems.map((item, index) => (
              <li key={index} style={{ width: '100%', padding: '0 1.5rem' }}>
                <Link
                  href={item.href || `/#${item.target}`}
                  onClick={(e) => {
                    item.action ? item.action() : handleNavClick(e, item.target);
                  }}
                  style={{
                    display: 'block',
                    padding: '0.8rem 1.2rem',
                    color: '#4b5563',
                    textDecoration: 'none',
                    fontSize: '1.02rem',
                    borderRadius: '10px',
                    transition: 'all 0.3s ease',
                    textAlign: 'center',
                  }}
                  role="menuitem"
                >
                  {item.label}
                </Link>
              </li>
            ))}
          </ul>
        )}
      </div>

      <style jsx>{`
        @keyframes slideDown {
          from {
            opacity: 0;
            transform: translateY(-12px) scale(0.98);
          }
          to {
            opacity: 1;
            transform: translateY(0) scale(1);
          }
        }
        @media (min-width: 768px) {
          .desktop-nav {
            display: flex !important;
          }
          .mobile-menu-button {
            display: none !important;
          }
          .mobile-dropdown-menu {
            display: none !important;
          }
        }
        @media (max-width: 767px) {
          .desktop-nav {
            display: none !important;
          }
        }
        body {
          padding-top: 72px;
        }
        html,
        body {
          margin: 0;
          padding: 0;
          height: 100%;
          width: 100%;
          box-sizing: border-box;
          font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        * {
          box-sizing: inherit;
        }
      `}</style>
    </nav>
  );
};

export default Navigation;