export default function VisionMisionSection({ isVisible, primaryBlue, openModal }) {
  return (
    <section className="py-5 bg-light">
      <div className="container px-4">
        <div className="row align-items-center g-5">
          <div className="col-lg-6 order-2 col-md-12">
            <div
              className="rounded-4 overflow-hidden shadow-lg cursor-pointer"
              data-idx="2"
              onClick={() => openModal("/image/visi-&-misi.jpg")}
              style={{
                height: "300px",
                backgroundColor: "#f8f9fa",
                cursor: "zoom-in",
                transition: "transform 0.3s ease, box-shadow 0.3s ease",
              }}
              onMouseEnter={(e) => {
                e.target.style.transform = "scale(1.02)";
                e.target.style.boxShadow = "0 16px 40px rgba(0,0,0,0.15)";
              }}
              onMouseLeave={(e) => {
                e.target.style.transform = "none";
                e.target.style.boxShadow = "0 12px 32px rgba(0,0,0,0.1)";
              }}
            >
              <img
                src="/image/visi-&-misi.jpg"
                alt="PT Graha Sarana Gresik"
                className="w-100 h-100"
                style={{ objectFit: "cover" }}
              />
            </div>
          </div>
          <div className="col-lg-6 order-1 col-md-12">
            <div
              data-idx="3"
              style={{
                opacity: isVisible["3"] ? 1 : 0,
                transform: isVisible["3"] ? "translateX(0)" : "translateX(-20px)",
                transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.4s",
              }}
            >
              <h2 className="fw-bold" style={{ color: primaryBlue }}>
                Visi
              </h2>
              <p className="text-dark mt-3 fs-5">
                Menjadi perusahaan yang sehat dan berkembang di bidang properti, angkutan, perdagangan, pergudangan, perkantoran, dan jasa bongkar muat.
              </p>
            </div>
            <div
              className="mt-5"
              data-idx="4"
              style={{
                opacity: isVisible["4"] ? 1 : 0,
                transform: isVisible["4"] ? "translateX(0)" : "translateX(-20px)",
                transition: "all 1s cubic-bezier(0.4, 0, 0.2, 1) 0.5s",
              }}
            >
              <h2 className="fw-bold" style={{ color: primaryBlue }}>
                Misi
              </h2>
              <ul className="list-unstyled mt-3 text-dark fs-5">
                {[
                  "Berorientasi pada pelayanan yang prima.",
                  "Menjunjung tinggi nilai-nilai integritas, profesionalisme, dan tanggung jawab.",
                  "Meningkatkan kualitas sumber daya manusia secara berkelanjutan.",
                  "Memberikan nilai tambah kepada pelanggan, pemegang saham, dan masyarakat.",
                ].map((item, idx) => (
                  <li key={idx} className="mb-3 d-flex align-items-start">
                    <span className="me-3 fs-4" style={{ color: primaryBlue }}>
                      ✓
                    </span>
                    <span>{item}</span>
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}