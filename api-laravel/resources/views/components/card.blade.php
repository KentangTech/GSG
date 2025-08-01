<div class="card-container d-grid" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 4px; padding: 1rem;">
  <div
    class="card card-modern border-0 shadow-sm position-relative overflow-hidden mx-auto"
    style="border-radius: 16px; max-width: 140px; min-height: 160px; transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);"
    data-bs-toggle="tooltip"
    title="{{ $title }}"
    onmouseover="this.style.transform='translateY(-8px)';"
    onmouseout="this.style.transform='translateY(0)';"
  >
    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-2 gap-1 z-2">
      <div
        class="p-1 rounded-circle d-flex align-items-center justify-content-center"
        style="
          font-size: 1.5rem;
          width: 48px;
          height: 48px;
          background-color: var(--bs-{{ $bg ?? 'primary' }}-100);
          color: var(--bs-{{ $color ?? 'primary' }});
          transition: transform 0.3s ease;
        "
      >
        <i class="{{ $icon }}"></i>
      </div>

      <h3
        class="fw-bold text-dark mb-0"
        style="
          font-size: 1.2rem;
          line-height: 1.1;
          word-break: break-word;
          max-width: 100%;
        "
      >
        {{ $value }}
      </h3>

      <p
        class="text-muted mb-0"
        style="
          font-size: 0.85rem;
          font-weight: 500;
          white-space: nowrap;
          text-overflow: ellipsis;
          overflow: hidden;
          max-width: 100%;
        "
        title="{{ $title }}"
      >
        {{ $title }}
      </p>

      <small
        class="d-flex align-items-center fw-semibold mt-1"
        style="color: {{ $trend === 'up' ? '#198754' : '#dc3545' }}; font-size: 0.8rem;"
      >
        <i class="fas fa-arrow-{{ $trend === 'up' ? 'up' : 'down' }} me-1"></i>
        <span>{{ $trendValue }}%</span>
      </small>
    </div>

    <div
      class="position-absolute bottom-0 end-0 opacity-10"
      style="
        font-size: 1.5rem;
        padding: 0.3rem;
        pointer-events: none;
        user-select: none;
      "
    >
      <i class="fas fa-circle-notch text-{{ $bg ?? 'primary' }}"></i>
    </div>
  </div>
</div>
