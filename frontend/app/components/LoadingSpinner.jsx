import React from 'react';
import styles from '@/app/css/LoadingSpinner.module.css';

const LoadingSpinner = () => {
  return (
    <div className={styles.loadingOverlay}>
      <div className={styles.loadingContainer}>
        {/* Icon Pencarian dengan Animasi */}
        <div className={styles.searchAnimation}>
          <div className={styles.searchIcon}>
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M11 19C15.4183 19 19 15.4183 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11C3 15.4183 6.58172 19 11 19Z"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
              <path
                d="M21 21L16.65 16.65"
                stroke="currentColor"
                strokeWidth="2"
                strokeLinecap="round"
                strokeLinejoin="round"
              />
            </svg>
          </div>

          {/* Aliran Data (Packet Animasi) */}
          <div className={styles.dataStream}>
            {[...Array(5)].map((_, i) => (
              <div
                key={i}
                className={styles.dataPacket}
                style={{ animationDelay: `${i * 0.15}s` }}
              ></div>
            ))}
          </div>
        </div>

        {/* Teks Loading */}
        <p className={styles.loadingText}>Loading...</p>
      </div>
    </div>
  );
};

export default LoadingSpinner;