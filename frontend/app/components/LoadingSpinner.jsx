import React from 'react';
import styles from '@/app/css/LoadingSpinner.module.css';

const LoadingSpinner = () => {
  return (
    <div className={styles.loadingOverlay}>
      <div className={styles.loadingContainer}>
        {/* Animasi Pencarian Data */}
        <div className={styles.searchAnimation}>
          <div className={styles.searchIcon}></div>
          <div className={styles.dataStream}>
            {[...Array(5)].map((_, i) => (
              <div 
                key={i} 
                className={styles.dataPacket}
                style={{ animationDelay: `${i * 0.2}s` }}
              ></div>
            ))}
          </div>
        </div>
        <p className={styles.loadingText}>Mencari Data...</p>
      </div>
    </div>
  );
};

export default LoadingSpinner;