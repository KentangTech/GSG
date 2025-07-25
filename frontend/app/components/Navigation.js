"use client";
import { useEffect, useState } from "react";
import { Navbar, Nav, Container, NavDropdown } from "react-bootstrap";
import Link from "next/link";

export default function Navigation() {
  const [scrolled, setScrolled] = useState(false);
  const [showNavbar, setShowNavbar] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      const scrollTop = window.scrollY || window.pageYOffset;
      const threshold = window.innerHeight * 0.8;

      const shouldShow = scrollTop > threshold;
      setShowNavbar(shouldShow);
      setScrolled(shouldShow);
    };

    handleScroll();
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const linkStyle = scrolled ? "text-dark" : "text-light";

  const scrollToTop = (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };

  return (
    <Navbar
      expand="lg"
      fixed="top"
      className={`modern-navbar py-3 ${
        showNavbar ? "bg-white shadow-sm" : "bg-transparent"
      }`}
      style={{
        transition: "background-color 0.4s ease, box-shadow 0.4s ease",
        opacity: showNavbar ? 1 : 0,
        visibility: showNavbar ? "visible" : "hidden",
        pointerEvents: showNavbar ? "auto" : "none",
      }}
    >
      <Container>
        {/* Logo */}
        <Link
          href="/"
          className={`navbar-brand fw-bold fs-4 ${linkStyle}`}
          onClick={scrollToTop}
          style={{ cursor: "pointer" }}
        >
          <img
            src="/image/GSG-kecil.png"
            alt="Logo"
            height={50}
            style={{ objectFit: "contain" }}
          />
        </Link>

        {/* Toggle Button */}
        <Navbar.Toggle aria-controls="basic-navbar-nav" className="border-0" />

        {/* Menu Navigasi */}
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="ms-auto gap-3">
            <Nav.Item>
              <Nav.Link
                as="button"
                onClick={scrollToTop}
                onKeyDown={(e) => e.key === "Enter" && scrollToTop()}
                tabIndex={0}
                className={`${linkStyle}`}
                style={{ textDecoration: "none", cursor: "pointer" }}
              >
                Home
              </Nav.Link>
            </Nav.Item>

            {/* About Us */}
            <Nav.Item>
              <Nav.Link
                as={Link}
                href="#about"
                className={`${linkStyle}`}
                style={{ textDecoration: "none" }}
              >
                About Us
              </Nav.Link>
            </Nav.Item>

            {/* Kebijakan */}
            <Nav.Item>
              <Nav.Link
                as={Link}
                href="#kebijakan"
                className={`${linkStyle}`}
                style={{ textDecoration: "none" }}
              >
                Kebijakan
              </Nav.Link>
            </Nav.Item>

            {/* News */}
            <Nav.Item>
              <Nav.Link
                as={Link}
                href="#news"
                className={`${linkStyle}`}
                style={{ textDecoration: "none" }}
              >
                News
              </Nav.Link>
            </Nav.Item>

            {/* socialmedia */}
            <Nav.Item>
              <Nav.Link
                as={Link}
                href="#medsos"
                className={`${linkStyle}`}
                style={{ textDecoration: "none" }}
              >
                Media Sosial
              </Nav.Link>
            </Nav.Item>
          </Nav>
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
}