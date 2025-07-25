export default function RelatedNews() {
  const relatedNews = [
    {
      title: "Judul Berita Terkait 1",
      image: "/image/news2.jpg",
    },
    {
      title: "Judul Berita Terkait 2",
      image: "/image/news3.jpg",
    },
    {
      title: "Judul Berita Terkait 3",
      image: "/image/news4.jpg",
    },
  ];

  return (
    <section style={{ marginTop: "4rem" }}>
      <h2 style={{ fontSize: "1.5rem", fontWeight: "bold", marginBottom: "1.5rem" }}>
        Berita Terkait
      </h2>
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "1rem",
        }}
      >
        {relatedNews.map((item, idx) => (
          <div
            key={idx}
            style={{
              display: "flex",
              gap: "1rem",
              alignItems: "center",
              cursor: "pointer",
              padding: "10px",
              borderRadius: "8px",
              transition: "background 0.3s",
            }}
            onClick={() => (window.location.href = `/news/${item.title.toLowerCase().replace(/\s+/g, "-")}`)}
            onMouseEnter={(e) => (e.currentTarget.style.backgroundColor = "#f0f0f0")}
            onMouseLeave={(e) => (e.currentTarget.style.backgroundColor = "white")}
          >
            <div
              style={{
                width: "100px",
                height: "80px",
                backgroundColor: "#ddd",
                overflow: "hidden",
                borderRadius: "8px",
              }}
            >
              <img
                src={item.image}
                alt={item.title}
                style={{
                  width: "100%",
                  height: "100%",
                  objectFit: "cover",
                }}
              />
            </div>
            <h3 style={{ margin: "0", fontSize: "1rem", fontWeight: "bold" }}>
              {item.title}
            </h3>
          </div>
        ))}
      </div>
    </section>
  );
}