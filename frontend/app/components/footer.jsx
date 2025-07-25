"use client";
import styles from "@/app/css/footer.module.css";
import React, { useState } from "react";
import {
  IoCall,
  IoMail,
  IoLocation,
  IoLogoTwitter,
  IoLogoFacebook,
  IoLogoInstagram,
  IoHeart,
} from "react-icons/io5";
import Image from 'next/image';

const Footer = () => {
  const SectionLinks = [
    { name: "Home", url: "/" },
    { name: "About US", url: "#about" },
    { name: "Kebijakan", url: "#kebijakan" },
    { name: "News", url: "#news" },
    { name: "Media Sosial", url: "#medsos" },
  ];

  const pageLinks = [
    { name: "About", url: "/about" },
    { name: "News", url: "/news" },
  ];

  const informationDetails = [
    {
      id: 1,
      icon: <IoCall size={16} className={styles.contactDetailIconSvg} />,
      value: "+62 31 3985 544",
      isEmail: false,
    },
    {
      id: 2,
      icon: <IoMail size={16} className={styles.contactDetailIconSvg} />,
      value: "get_office@grahasaranagresik.com",
      isEmail: true,
    },
    {
      id: 3,
      icon: <IoLocation size={16} className={styles.contactDetailIconSvg} />,
      value: "Karangturi, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61121",
      isEmail: false,
    },
  ];

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    subject: "",
    message: "",
  });

  const [status, setStatus] = useState("");
  const [errorMessage, setErrorMessage] = useState("");

  const getCurrentYear = () => {
    return new Date().getFullYear();
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const getLoadingText = () => {
    const dots = '.'.repeat((Date.now() / 500) % 4);
    return `Sending${dots}`;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("loading");
    setErrorMessage("");

    try {
      await new Promise((resolve) => setTimeout(resolve, 2000));
      setStatus("success");
      setFormData({ name: "", email: "", subject: "", message: "" });
      setTimeout(() => setStatus(""), 3000);
    } catch (error) {
      console.error("Error sending message:", error);
      setStatus("error");
      setErrorMessage("Failed to send message. Please try again later.");
      setTimeout(() => setStatus(""), 3000);
    }
  };

  return (
    <footer className={`${styles.footerContainer} ${styles.compactFooter}`}>
      <div className="container">
        {/* Row Utama untuk Desktop - Semua elemen sejajar horizontal */}
        <div className="row mt-2 d-none d-lg-flex align-items-start">
          {/* Logo and Description - col-lg-3 */}
          <div className="col-lg-3 mb-lg-0 pe-4">
            <div className="mb-3">
              <Image
                src="/image/GSG-kecil.png"
                alt="PT Graha Sarana Gresik Logo"
                width={220}
                height={110}
                className="img-fluid"
              />
            </div>
            <div className={`${styles.footerDescription} ${styles.compactCopyright}`}>
              PT Graha Sarana Gresik adalah perusahaan yang bergerak di bidang konstruksi dan pengembangan properti.
            </div>
          </div>

          {/* Vertical Divider */}
          <div className={`${styles.verticalDivider} d-none d-lg-block`}></div>

          {/* Section Links - col-lg-1 */}
          <div className="col-lg-1 col-md-1 ps-lg-2">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Section</h2>
            <ul className="list-unstyled">
              {SectionLinks.map((link, index) => (
                <li key={index} className="mb-2">
                  <a href={link.url} className={`${styles.footerLink} d-block py-1`}>
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Page Links - col-lg-1 */}
          <div className="col-lg-1">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Page</h2>
            <ul className="list-unstyled">
              {pageLinks.map((link, index) => (
                <li key={index} className="mb-2">
                  <a href={link.url} className={`${styles.footerLink} d-block py-1`}>
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Info - col-lg-3 */}
          <div className="col-lg-3">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Infomation</h2>
            <ul className="list-unstyled">
              {informationDetails.map((info) => (
                <li key={info.id} className={`${styles.contactDetailItem} ${styles.compactContactItem} mb-2`}>
                  <span className={styles.contactDetailIcon}>{info.icon}</span>
                  <span className={`${styles.contactDetailText} ${styles.compactContactText}`}>
                    {info.isEmail ? (
                      <a href={`mailto:${info.value}`} className={styles.footerLink}>
                        {info.value}
                      </a>
                    ) : (
                      info.value
                    )}
                  </span>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact Us Form - col-lg-4 */}
          <div className="col-lg-3 contactUsColumn">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Contact Us</h2>
            <form onSubmit={handleSubmit} className={`${styles.contactForm} ${styles.compactForm}`} style={{ padding: '1.2rem', borderRadius: '8px' }}>
              <div className="row">
                <div className="col-lg-6 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Name"
                      required
                      value={formData.name}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-lg-6 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Email"
                      required
                      value={formData.email}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-12 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="text"
                      id="subject"
                      name="subject"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Subject"
                      required
                      value={formData.subject}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-12 mb-3">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <textarea
                      id="message"
                      name="message"
                      rows="3"
                      className={`${styles.formControl} ${styles.textarea} ${styles.compactFormControl} ${styles.compactTextarea}`}
                      placeholder="Message"
                      required
                      value={formData.message}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem', minHeight: '80px' }}
                    ></textarea>
                  </div>
                </div>
                <div className="col-12 text-center">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup} mb-0`}>
                    <button
                      type="submit"
                      className={`${styles.submitButton} ${styles.compactButton} ${status === 'loading' ? styles.loadingButton : ''} ${status === 'success' ? styles.successButton : ''} ${status === 'error' ? styles.errorButton : ''}`}
                      disabled={status === "loading"}
                      style={{ padding: '0.6rem 1.2rem', fontSize: '0.85rem', width: '100%' }}
                    >
                      {status === "loading" ? (
                        <span className={styles.buttonText}>
                          {getLoadingText()}
                        </span>
                      ) : status === "success" ? (
                        <span className={styles.buttonText}>
                          Sent Successfully!
                        </span>
                      ) : status === "error" ? (
                        <span className={styles.buttonText}>
                          Error - Retry?
                        </span>
                      ) : (
                        <span className={styles.buttonText}>
                          Send Message
                        </span>
                      )}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        {/* Row untuk Tablet/Mobile */}
        <div className="row mt-2 d-lg-none">
          <div className="col-12 mb-4 text-center">
            <div className="mb-3 d-flex justify-content-center">
              <Image
                src="/image/GSG-kecil.png"
                alt="PT Graha Sarana Gresik Logo"
                width={180}
                height={90}
                className="img-fluid"
              />
            </div>
            <div className={`${styles.footerDescription} ${styles.compactCopyright}`}>
              PT Graha Sarana Gresik adalah perusahaan yang bergerak di bidang konstruksi dan pengembangan properti.
            </div>
          </div>
          
          <div className="col-12 mb-4">
            <div className="row">
              <div className="col-6">
                <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Section</h2>
                <ul className="list-unstyled">
                  {SectionLinks.map((link, index) => (
                    <li key={index} className="mb-2">
                      <a href={link.url} className={`${styles.footerLink} d-block py-1`}>
                        {link.name}
                      </a>
                    </li>
                  ))}
                </ul>
              </div>
              <div className="col-6">
                <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Page</h2>
                <ul className="list-unstyled">
                  {pageLinks.map((link, index) => (
                    <li key={index} className="mb-2">
                      <a href={link.url} className={`${styles.footerLink} d-block py-1`}>
                        {link.name}
                      </a>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>
          
          <div className="col-12 mb-4">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Contact Info</h2>
            <ul className="list-unstyled">
              {informationDetails.map((info) => (
                <li key={info.id} className={`${styles.contactDetailItem} ${styles.compactContactItem} mb-2`}>
                  <span className={styles.contactDetailIcon}>{info.icon}</span>
                  <span className={`${styles.contactDetailText} ${styles.compactContactText}`}>
                    {info.isEmail ? (
                      <a href={`mailto:${info.value}`} className={styles.footerLink}>
                        {info.value}
                      </a>
                    ) : (
                      info.value
                    )}
                  </span>
                </li>
              ))}
            </ul>
          </div>
          
          <div className="col-12 mb-4">
            <h2 className={`${styles.footerHeading} ${styles.compactHeading}`}>Contact Us</h2>
            <form onSubmit={handleSubmit} className={`${styles.contactForm} ${styles.compactForm}`} style={{ padding: '1.2rem', borderRadius: '8px' }}>
              <div className="row">
                <div className="col-12 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="text"
                      id="name-mobile"
                      name="name"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Name"
                      required
                      value={formData.name}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-12 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="email"
                      id="email-mobile"
                      name="email"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Email"
                      required
                      value={formData.email}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-12 mb-2">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <input
                      type="text"
                      id="subject-mobile"
                      name="subject"
                      className={`${styles.formControl} ${styles.compactFormControl}`}
                      placeholder="Subject"
                      required
                      value={formData.subject}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem' }}
                    />
                  </div>
                </div>
                <div className="col-12 mb-3">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup}`}>
                    <textarea
                      id="message-mobile"
                      name="message"
                      rows="4"
                      className={`${styles.formControl} ${styles.textarea} ${styles.compactFormControl} ${styles.compactTextarea}`}
                      placeholder="Message"
                      required
                      value={formData.message}
                      onChange={handleChange}
                      style={{ fontSize: '0.85rem', padding: '0.6rem', minHeight: '100px' }}
                    ></textarea>
                  </div>
                </div>
                <div className="col-12 text-center">
                  <div className={`${styles.formGroup} ${styles.compactFormGroup} mb-0`}>
                    <button
                      type="submit"
                      className={`${styles.submitButton} ${styles.compactButton} ${status === 'loading' ? styles.loadingButton : ''} ${status === 'success' ? styles.successButton : ''} ${status === 'error' ? styles.errorButton : ''}`}
                      disabled={status === "loading"}
                      style={{ padding: '0.7rem 1.3rem', fontSize: '0.9rem', width: '100%' }}
                    >
                      {status === "loading" ? (
                        <span className={styles.buttonText}>
                          {getLoadingText()}
                        </span>
                      ) : status === "success" ? (
                        <span className={styles.buttonText}>
                          Sent Successfully!
                        </span>
                      ) : status === "error" ? (
                        <span className={styles.buttonText}>
                          Error - Retry?
                        </span>
                      ) : (
                        <span className={styles.buttonText}>
                          Send Message
                        </span>
                      )}
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        
        {/* Copyright and Social Media */}
        <div className="row mt-3 pt-3 border-top">
          <div className="col-12">
            <div className="row align-items-center">
              <div className="col-md-6 mb-md-0 mb-3">
                <p className={`${styles.copyright} mb-0 ${styles.compactCopyright}`}>
                  Copyright &copy;
                  {getCurrentYear()} All rights reserved. | This Website is made
                  with <IoHeart aria-hidden="true" className={styles.heartIcon} size={12} />{" "}
                  by{" "}
                  <a>
                    PT Graha Sarana Gresik
                  </a>
                </p>
              </div>
              <div className="col-md-6 text-md-end text-center">
                <ul className={`${styles.ftcoFooterSocial} p-0 mb-0 d-flex justify-content-md-end justify-content-center`}>
                  <li className={`${styles.ftcoAnimate} mx-2`}>
                    <a href="#" title="Twitter" aria-label="Twitter" className={styles.socialIconLink}>
                      <IoLogoTwitter size={18} className={styles.socialIconSvg} />
                    </a>
                  </li>
                  <li className={`${styles.ftcoAnimate} mx-2`}>
                    <a href="#" title="Facebook" aria-label="Facebook" className={styles.socialIconLink}>
                      <IoLogoFacebook size={18} className={styles.socialIconSvg} />
                    </a>
                  </li>
                  <li className={`${styles.ftcoAnimate} mx-2`}>
                    <a href="#" title="Instagram" aria-label="Instagram" className={styles.socialIconLink}>
                      <IoLogoInstagram size={18} className={styles.socialIconSvg} />
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;