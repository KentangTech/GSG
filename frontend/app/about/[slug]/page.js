import Footer from "@/app/components/footer";
import { notFound } from "next/navigation";
import style from "@/app/css/AboutSlug.module.css"; // ✅ CSS Module diimpor

// Data bisnis
const bisnisData = [
  {
    title: "Perdagangan Umum",
    desc: "Menjalankan usaha di bidang perdagangan maupun impor antara lain : Spare part, Bahan baku Industri atau Chemical dan Pupuk, Mekanikal Rotaring dan Non Rotaring, Instrumen & Pipa Fitting.",
    image: "/bisnis/Perdagangan-Barang.jpg",
    tag: "DIV.PUBP",
  },
  {
    title: "Mustikarasa Cafe dan Resto",
    desc: "Menjalankan usaha dalam bidang Cafe & Resto yang dikelola secara profesional yang berarsitektur tradisional Jawa dengan sentuhan-sentuhan modern.",
    image: "/bisnis/Cafe-Dan-Resto.jpg",
    tag: "DIV.PERDAGANGAN JASA",
  },
  {
    title: "Perumahan Bella Casa",
    desc: "Menjalankan usaha di bidang perumahan yang mengedepankan lingkungan asri dan nyaman bagi penghuni.",
    image: "/bisnis/Perumahan-Bella-Casa.jpg",
    tag: "DIV.PROPERTY & PERUMAHAN",
  },
  {
    title: "Pergudangan",
    desc: "Menjalankan usaha di bidang jasa pergudangan yang aman, strategis, dan dilengkapi sistem keamanan terpadu.",
    image: "/bisnis/Pergudangan.jpg",
    tag: "DIV.PROPERTY & PERUMAHAN",
  },
  {
    title: "Property Perkantoran",
    desc: "Menjalankna usaha di bidang jasa sewa ruang dan manajemen pengelolaan gedung serta pembangunan infrastruktur perkantoran.",
    image: "/bisnis/Property-Perkantoran.jpg",
    tag: "DIV.PROPERTY & PERUMAHAN",
  },
  {
    title: "Jasa sewa alat berat & Kendaraan",
    desc: "Menjalankan usaha di bidang persewaan angkutan kendaraan seperti bis, mobil hingga alat berat.",
    image: "/bisnis/Jasa-Sewa-Kendaraan.jpg",
    tag: "DIV.PERDAGANGAN JASA",
  },
  {
    title: "Jasa Konstruksi",
    desc: "Menjalankan usaha di bidang konstruksi bangunan dan infrastruktur dengan tenaga ahli dan peralatan modern.",
    image: "/bisnis/Jasa-Konstruksi.jpg",
    tag: "DIV.PERDAGANGAN JASA",
  },
  {
    title: "Tour & Travel",
    desc: "Menyediakan layanan tour wisata dan travel umroh/haji dengan pelayanan prima dan harga kompetitif.",
    image: "/bisnis/Tour-Dan-Travel.jpg",
    tag: "GRESIK GRAHA WISATA",
  },
];

function generateSlug(title) {
  return title.toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "");
}

export default function BisnisDetailPage({ params }) {
  const { slug } = params;
  const item = bisnisData.find((item) => generateSlug(item.title) === slug);

  if (!item) {
    notFound();
  }

  return (
    <div style={{ padding: "2rem 1rem", backgroundColor: "#f7fafc", minHeight: "100vh" }}>
      {/* Kontainer Utama */}
      <div className={style.pageContainer}>
        {/* Tombol Kembali */}
        <div className={style.backButtonContainer}>
          <a href="/about" className={style.backButton}>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="18"
              height="18"
              fill="currentColor"
              viewBox="0 0 24 24"
              className={style.arrowIcon}
            >
              <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
            </svg>
            <span>Kembali ke Bisnis</span>
          </a>
        </div>

        {/* Konten Utama */}
        <div className="row g-6 align-items-center">
          {/* Gambar */}
          <div className="col-12 col-lg-6">
            <div className={style.imageWrapper}>
              <img src={item.image} alt={item.title} />
            </div>
          </div>

          {/* Teks */}
          <div className="col-12 col-lg-6">
            <div className={style.contentWrapper}>
              {/* Tag */}
              {item.tag && <span className={style.tag}>{item.tag}</span>}

              {/* Judul */}
              <h1 className={style.title}>{item.title}</h1>

              {/* Deskripsi */}
              <p className={style.description}>{item.desc}</p>

              {/* Tombol CTA */}
              <div className={style.ctaButtonContainer}>
                <button className={style.ctaButton}>
                  Hubungi Kami untuk Informasi Lebih Lanjut
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Footer */}
        <footer className="mt-16">
          <Footer />
        </footer>
      </div>
    </div>
  );
}