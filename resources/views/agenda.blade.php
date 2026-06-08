<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Agenda · {{ $tenant->name }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;600;700;900&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<style>
  :root { --lime:#76FF03; --dark:#0f1f0f; --card:#161f16; --border:#1e3a1e; --white:#fff; --muted:rgba(204,255,144,.6); }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html{scroll-behavior:smooth}
  body{background:var(--dark);color:var(--white);font-family:'Barlow',sans-serif;min-height:100vh}

  /* NAV */
  nav{display:flex;align-items:center;justify-content:space-between;padding:16px 24px;border-bottom:1px solid var(--border);}
  .logo{display:flex;align-items:center;gap:10px}
  .logo-mark{position:relative;width:32px;height:32px;display:flex;align-items:center;justify-content:center}
  .logo-mark svg{width:32px;height:32px}
  .logo-text{font-size:15px;font-weight:700;letter-spacing:.5px}
  .logo-text span{color:var(--lime)}
  .logo-by{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:rgba(118,255,3,.4);display:block;margin-top:-2px}
  .studio-badge{background:rgba(118,255,3,.08);border:1px solid rgba(118,255,3,.2);border-radius:20px;padding:6px 14px;font-size:12px;color:var(--lime);font-weight:600;letter-spacing:.5px}

  /* HERO */
  .hero{padding:40px 24px 24px;text-align:center}
  .hero-avatar{width:80px;height:80px;border-radius:50%;background:var(--lime);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;color:#0A0A0A;margin:0 auto 16px;border:3px solid rgba(118,255,3,.3)}
  .hero h1{font-size:24px;font-weight:900;margin-bottom:4px}
  .hero-meta{font-size:13px;color:var(--muted);display:flex;align-items:center;justify-content:center;gap:8px}
  .hero-dot{width:4px;height:4px;border-radius:50%;background:rgba(118,255,3,.4)}

  /* STEPS */
  .steps{display:flex;align-items:center;justify-content:center;gap:0;padding:24px;position:sticky;top:0;background:var(--dark);z-index:10;border-bottom:1px solid var(--border)}
  .step{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1;max-width:80px}
  .step-num{width:28px;height:28px;border-radius:50%;background:var(--border);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:rgba(255,255,255,.3);transition:.3s}
  .step.active .step-num{background:var(--lime);color:#0A0A0A}
  .step.done .step-num{background:rgba(118,255,3,.2);color:var(--lime)}
  .step-label{font-size:9px;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.3);transition:.3s}
  .step.active .step-label,.step.done .step-label{color:rgba(118,255,3,.7)}
  .step-line{flex:1;height:1px;background:var(--border);margin-bottom:16px;max-width:40px}
  .step.done + .step-line{background:rgba(118,255,3,.3)}

  /* SCREENS */
  .screen{display:none;padding:24px;max-width:480px;margin:0 auto}
  .screen.active{display:block}

  /* SECTION LABEL */
  .sec-label{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:rgba(118,255,3,.5);font-weight:600;margin-bottom:12px}

  /* SERVICES */
  .services{display:flex;flex-direction:column;gap:10px;margin-bottom:28px}
  .svc-card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:14px 16px;display:flex;align-items:center;justify-content:space-between;cursor:pointer;transition:.2s}
  .svc-card:hover,.svc-card.active{border-color:var(--lime);background:rgba(118,255,3,.05)}
  .svc-card.active .svc-check{opacity:1}
  .svc-name{font-size:15px;font-weight:700}
  .svc-info{font-size:12px;color:rgba(255,255,255,.4);margin-top:2px}
  .svc-right{display:flex;align-items:center;gap:10px}
  .svc-price{font-size:15px;font-weight:700;color:var(--lime)}
  .svc-check{width:20px;height:20px;border-radius:50%;background:var(--lime);display:flex;align-items:center;justify-content:center;font-size:11px;color:#0A0A0A;font-weight:900;opacity:0;transition:.2s;flex-shrink:0}

  /* DATES */
  .dates{display:flex;gap:8px;overflow-x:auto;padding-bottom:4px;margin-bottom:28px;scrollbar-width:none}
  .dates::-webkit-scrollbar{display:none}
  .date-btn{flex-shrink:0;background:var(--card);border:1px solid var(--border);border-radius:12px;padding:10px 12px;text-align:center;cursor:pointer;transition:.2s;min-width:52px}
  .date-btn:hover,.date-btn.active{border-color:var(--lime);background:rgba(118,255,3,.05)}
  .date-btn.active .date-day,.date-btn.active .date-num{color:var(--lime)}
  .date-day{font-size:10px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px}
  .date-num{font-size:18px;font-weight:700;margin:2px 0}
  .date-month{font-size:10px;color:rgba(255,255,255,.3)}

  /* SLOTS */
  .slots-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:28px;min-height:60px}
  .slot-btn{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:11px 6px;text-align:center;font-size:14px;font-weight:600;cursor:pointer;transition:.2s}
  .slot-btn:hover,.slot-btn.active{border-color:var(--lime);background:rgba(118,255,3,.05);color:var(--lime)}
  .slot-btn.blocked{opacity:.3;pointer-events:none;border-style:dashed}
  .slots-empty{grid-column:1/-1;text-align:center;color:rgba(255,255,255,.3);font-size:13px;padding:20px 0}
  .slots-loading{grid-column:1/-1;text-align:center;color:rgba(118,255,3,.5);font-size:13px;padding:20px 0}

  /* CONFIRM CARD */
  .confirm-card{background:#1B3A1B;border-radius:14px;padding:16px;margin-bottom:20px}
  .confirm-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(118,255,3,.1)}
  .confirm-row:last-child{border-bottom:none}
  .confirm-label{font-size:11px;color:rgba(204,255,144,.5);text-transform:uppercase;letter-spacing:1px}
  .confirm-val{font-size:14px;font-weight:600}
  .confirm-val.green{color:var(--lime)}

  /* INPUTS */
  .input-group{margin-bottom:12px}
  .input-group label{display:block;font-size:11px;letter-spacing:1px;text-transform:uppercase;color:rgba(118,255,3,.5);margin-bottom:6px}
  .input-field{width:100%;background:var(--card);border:1px solid var(--border);border-radius:10px;padding:12px 14px;font-size:15px;color:var(--white);font-family:inherit;transition:.2s}
  .input-field:focus{outline:none;border-color:var(--lime)}
  .input-field::placeholder{color:rgba(255,255,255,.2)}

  /* BUTTONS */
  .btn-primary{width:100%;background:var(--lime);color:#0A0A0A;border:none;border-radius:12px;padding:15px;font-size:14px;font-weight:800;letter-spacing:1px;text-transform:uppercase;cursor:pointer;transition:.15s;font-family:inherit}
  .btn-primary:hover{background:#8fff2a}
  .btn-primary:disabled{opacity:.4;cursor:not-allowed}
  .btn-ghost{width:100%;background:transparent;border:none;color:rgba(255,255,255,.3);font-size:12px;padding:12px;cursor:pointer;font-family:inherit;margin-top:4px}

  /* SUCCESS */
  .success-wrap{text-align:center;padding:20px 0}
  .success-icon{font-size:56px;margin-bottom:16px}
  .success-title{font-size:28px;font-weight:900;color:var(--lime);margin-bottom:8px}
  .success-sub{font-size:15px;color:var(--muted);line-height:1.7}
  .wa-btn{width:100%;background:transparent;border:2px solid var(--lime);border-radius:12px;padding:14px;font-size:14px;font-weight:700;color:var(--lime);letter-spacing:.5px;cursor:pointer;margin-top:20px;text-transform:uppercase;font-family:inherit;display:flex;align-items:center;justify-content:center;gap:8px}

  /* FOOTER */
  footer{text-align:center;padding:32px 24px;border-top:1px solid var(--border);margin-top:40px}
  .footer-brand{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:rgba(118,255,3,.35)}

  @keyframes dot-blink{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.6)}}
  .dot-pulse{display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--lime);animation:dot-blink 1.6s ease-in-out infinite;box-shadow:0 0 8px rgba(118,255,3,.8)}
</style>
</head>
<body>

<nav>
  <div class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="16" cy="16" r="7" stroke="#76FF03" stroke-width="1.5"/>
        <circle cx="16" cy="16" r="13" stroke="#76FF03" stroke-width=".5" stroke-dasharray="3 3" opacity=".4"/>
        <circle cx="16" cy="16" r="4" fill="#76FF03"/>
      </svg>
    </div>
    <div>
      <div class="logo-text">estamos<span>online</span></div>
      <span class="logo-by">by nyx technology</span>
    </div>
  </div>
  <div class="studio-badge">{{ $tenant->business_type }}</div>
</nav>

<div class="hero">
  <div class="hero-avatar">{{ strtoupper(substr($tenant->name, 0, 2)) }}</div>
  <h1>{{ $tenant->name }}</h1>
  <div class="hero-meta">
    <span>{{ $tenant->business_type }}</span>
    <div class="hero-dot"></div>
    <span class="dot-pulse"></span>
    <span>Online agora</span>
  </div>
</div>

<div class="steps">
  <div class="step active" id="step1">
    <div class="step-num">1</div>
    <div class="step-label">Serviço</div>
  </div>
  <div class="step-line"></div>
  <div class="step" id="step2">
    <div class="step-num">2</div>
    <div class="step-label">Horário</div>
  </div>
  <div class="step-line"></div>
  <div class="step" id="step3">
    <div class="step-num">3</div>
    <div class="step-label">Confirmar</div>
  </div>
</div>

<!-- TELA 1: Serviço -->
<div class="screen active" id="screen1">
  <div class="sec-label">Escolha o serviço</div>
  <div class="services">
    @foreach($services as $service)
    <div class="svc-card"
         data-id="{{ $service->id }}"
         data-name="{{ $service->name }}"
         data-duration="{{ $service->duration_minutes }}"
         data-price="R$ {{ number_format($service->price, 2, ',', '.') }}"
         onclick="selectService(this)">
      <div>
        <div class="svc-name">{{ $service->name }}</div>
        <div class="svc-info">
          {{ $service->duration_minutes >= 60 ? floor($service->duration_minutes/60).'h'.($service->duration_minutes%60 ? ($service->duration_minutes%60).'min' : '') : $service->duration_minutes.'min' }}
        </div>
      </div>
      <div class="svc-right">
        <div class="svc-price">R$ {{ number_format($service->price, 2, ',', '.') }}</div>
        <div class="svc-check">✓</div>
      </div>
    </div>
    @endforeach
  </div>
  <button class="btn-primary" id="btn-step1" onclick="goStep2()" disabled>Escolher horário →</button>
</div>

<!-- TELA 2: Data e Horário -->
<div class="screen" id="screen2">
  <div class="sec-label">Escolha o dia</div>
  <div class="dates" id="dates-row"></div>

  <div class="sec-label">Horários disponíveis</div>
  <div class="slots-grid" id="slots-grid">
    <div class="slots-loading">Selecione um dia</div>
  </div>

  <button class="btn-primary" id="btn-step2" onclick="goStep3()" disabled>Continuar →</button>
  <button class="btn-ghost" onclick="goBack(1)">← Voltar</button>
</div>

<!-- TELA 3: Confirmação -->
<div class="screen" id="screen3">
  <div class="sec-label">Resumo do agendamento</div>
  <div class="confirm-card">
    <div class="confirm-row">
      <div class="confirm-label">Serviço</div>
      <div class="confirm-val" id="res-svc"></div>
    </div>
    <div class="confirm-row">
      <div class="confirm-label">Data</div>
      <div class="confirm-val" id="res-date"></div>
    </div>
    <div class="confirm-row">
      <div class="confirm-label">Horário</div>
      <div class="confirm-val" id="res-time"></div>
    </div>
    <div class="confirm-row">
      <div class="confirm-label">Valor</div>
      <div class="confirm-val green" id="res-price"></div>
    </div>
  </div>

  <div class="sec-label">Seus dados</div>
  <div class="input-group">
    <label>Nome completo</label>
    <input class="input-field" type="text" id="client-name" placeholder="Como prefere ser chamado(a)?">
  </div>
  <div class="input-group">
    <label>WhatsApp</label>
    <input class="input-field" type="tel" id="client-wa" placeholder="(31) 9 0000-0000">
  </div>

  <button class="btn-primary" onclick="confirmarAgendamento()">Confirmar agendamento</button>
  <button class="btn-ghost" onclick="goBack(2)">← Voltar</button>
</div>

<!-- TELA 4: Sucesso -->
<div class="screen" id="screen4">
  <div class="success-wrap">
    <div class="success-icon">✅</div>
    <div class="success-title">Agendado!</div>
    <div class="success-sub">Seu horário foi confirmado.<br>Em breve você receberá a confirmação<br>pelo WhatsApp.</div>
  </div>
  <div class="confirm-card" style="margin-top:24px">
    <div class="confirm-row">
      <div class="confirm-label">Serviço</div>
      <div class="confirm-val" id="res-svc2"></div>
    </div>
    <div class="confirm-row">
      <div class="confirm-label">Data e hora</div>
      <div class="confirm-val" id="res-datetime"></div>
    </div>
    <div class="confirm-row">
      <div class="confirm-label">Profissional</div>
      <div class="confirm-val">{{ $tenant->name }}</div>
    </div>
  </div>
  <button class="wa-btn" onclick="abrirWhatsApp()">
    📱 Falar com {{ $tenant->name }}
  </button>
</div>

<footer>
  <div class="footer-brand">⊙ estamos online · by nyx technology</div>
</footer>

<script>
const SLUG = '{{ $tenant->slug }}';
const WA   = '{{ $tenant->whatsapp }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

let sel = { serviceId: null, serviceName: '', servicePrice: '', date: '', dateLabel: '', time: '' };

function selectService(el) {
  document.querySelectorAll('.svc-card').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  sel.serviceId   = el.dataset.id;
  sel.serviceName = el.dataset.name;
  sel.servicePrice = el.dataset.price;
  document.getElementById('btn-step1').disabled = false;
}

function goStep2() {
  setScreen(2);
  buildDates();
}

function buildDates() {
  const row = document.getElementById('dates-row');
  row.innerHTML = '';
  const days = ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'];
  const months = ['jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez'];
  const today = new Date();
  for (let i = 0; i < 14; i++) {
    const d = new Date(today);
    d.setDate(today.getDate() + i);
    const btn = document.createElement('div');
    btn.className = 'date-btn';
    btn.innerHTML = `<div class="date-day">${days[d.getDay()]}</div><div class="date-num">${d.getDate()}</div><div class="date-month">${months[d.getMonth()]}</div>`;
    const iso = d.toISOString().split('T')[0];
    const label = `${days[d.getDay()]}, ${d.getDate()} de ${months[d.getMonth()]}`;
    btn.onclick = () => selectDate(btn, iso, label);
    row.appendChild(btn);
  }
}

function selectDate(el, iso, label) {
  document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  sel.date = iso;
  sel.dateLabel = label;
  loadSlots();
}

function loadSlots() {
  const grid = document.getElementById('slots-grid');
  grid.innerHTML = '<div class="slots-loading">Carregando...</div>';
  document.getElementById('btn-step2').disabled = true;

  fetch(`/agenda/${SLUG}/slots?service_id=${sel.serviceId}&date=${sel.date}`)
    .then(r => r.json())
    .then(slots => {
      grid.innerHTML = '';
      if (!slots.length) {
        grid.innerHTML = '<div class="slots-empty">Nenhum horário disponível neste dia</div>';
        return;
      }
      slots.forEach(time => {
        const btn = document.createElement('div');
        btn.className = 'slot-btn';
        btn.textContent = time;
        btn.onclick = () => selectSlot(btn, time);
        grid.appendChild(btn);
      });
    })
    .catch(() => {
      grid.innerHTML = '<div class="slots-empty">Erro ao carregar horários</div>';
    });
}

function selectSlot(el, time) {
  document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  sel.time = time;
  document.getElementById('btn-step2').disabled = false;
}

function goStep3() {
  document.getElementById('res-svc').textContent   = sel.serviceName;
  document.getElementById('res-date').textContent  = sel.dateLabel;
  document.getElementById('res-time').textContent  = sel.time;
  document.getElementById('res-price').textContent = sel.servicePrice;
  setScreen(3);
}

function confirmarAgendamento() {
  const name = document.getElementById('client-name').value.trim();
  const wa   = document.getElementById('client-wa').value.trim();
  if (!name || !wa) { alert('Preencha seu nome e WhatsApp'); return; }

  const btn = event.target;
  btn.disabled = true;
  btn.textContent = 'Aguarde...';

  fetch(`/agenda/${SLUG}/agendar`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
    body: JSON.stringify({
      service_id:      sel.serviceId,
      date:            sel.date,
      time:            sel.time,
      client_name:     name,
      client_whatsapp: wa,
    })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('res-svc2').textContent      = sel.serviceName;
      document.getElementById('res-datetime').textContent  = sel.dateLabel + ' · ' + sel.time;
      setScreen(4);
    }
  })
  .catch(() => {
    btn.disabled = false;
    btn.textContent = 'Confirmar agendamento';
    alert('Erro ao agendar. Tente novamente.');
  });
}

function abrirWhatsApp() {
  const msg = encodeURIComponent(`Olá! Acabei de agendar ${sel.serviceName} para ${sel.dateLabel} às ${sel.time}.`);
  window.open(`https://wa.me/55${WA}?text=${msg}`, '_blank');
}

function goBack(step) { setScreen(step); }

function setScreen(n) {
  document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
  document.getElementById('screen' + n).classList.add('active');
  [1,2,3].forEach(i => {
    const s = document.getElementById('step' + i);
    s.classList.remove('active','done');
    if (i < n) s.classList.add('done');
    if (i === n) s.classList.add('active');
  });
  window.scrollTo({top:0, behavior:'smooth'});
}
</script>
</body>
</html>
