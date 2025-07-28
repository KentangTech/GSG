import React from 'react';

const LoadingSpinner = () => {
  return (
    <div style={{
      position: 'fixed',
      top: 0,
      left: 0,
      width: '100%',
      height: '100%',
      backgroundColor: '#fff',
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      zIndex: 9999,
    }}>
      <img
        src="/icons/Loading.gif"
        alt="Loading..."
        style={{
          width: '100px',
          height: '100px',
        }}
      />
    </div>
  );
};

export default LoadingSpinner;