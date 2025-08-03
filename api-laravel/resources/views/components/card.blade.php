<div class="card-container d-grid gap-3"
     style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); padding: 1rem;">

    <div
        class="card card-modern border-0 shadow-sm position-relative mx-auto text-center"
        style="
            border-radius: 12px;
            max-width: 270px;
            width: 100%;
            min-height: 180px;
            background-color: #ffffff;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        "
        data-bs-toggle="tooltip"
        title="{{ $title }}"
        onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 12px 24px rgba(67, 97, 238, 0.18)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.08)';"
    >
        <!-- Background dekoratif dari tema utama -->
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 rounded"
             style="background: linear-gradient(140deg, #ffffff, #ffffff);"></div>

        <!-- Card Body -->
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-3 gap-1 position-relative z-2">

            <!-- Ikon dengan warna utama -->
            <div
                class="p-2 rounded-circle d-flex align-items-center justify-content-center mb-2"
                style="
                    font-size: 1.5rem;
                    width: 50px;
                    height: 50px;
                    background-color: #4361ee;
                    color: white;
                    transition: transform 0.3s ease;
                "
            >
                <i class="{{ $icon }}"></i>
            </div>

            <!-- Nilai -->
            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.3rem; line-height: 1.2;">
                {{ $value }}
            </h3>

            <!-- Judul -->
            <p
                class="text-muted mb-1"
                style="
                    font-size: 0.85rem;
                    font-weight: 500;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    max-width: 100%;
                "
                title="{{ $title }}"
            >
                {{ $title }}
            </p>

            <!-- Trend -->
            <small
                class="d-flex align-items-center fw-semibold"
                style="font-size: 0.8rem;"
            >
                <i class="fas fa-arrow-{{ $trend === 'up' ? 'up' : 'down' }} me-1"
                   style="color: {{ $trend === 'up' ? '#198754' : '#dc3545' }};"></i>
                <span style="color: {{ $trend === 'up' ? '#198754' : '#dc3545' }};">{{ $trendValue }}%</span>
            </small>
        </div>

        <!-- Dekorasi sudut bawah kanan -->
        <div class="position-absolute bottom-0 end-0 opacity-10"
             style="font-size: 1.7rem; pointer-events: none;">
        </div>
    </div>
</div>
