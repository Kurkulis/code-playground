document.addEventListener("DOMContentLoaded", () => {
  const openBtn = document.getElementById("open-modal");
  const modal = document.getElementById("register-modal");
  const dialog = modal.querySelector(".modal__dialog");
  const backdrop = modal.querySelector(".modal__backdrop");
  const closeBtns = modal.querySelectorAll('[data-dismiss="modal"]');
  const form = document.getElementById("register-form");

  const waves = dialog.querySelector(".waves");
  const agent = dialog.querySelector(".agent");
  const submitState = dialog.querySelector("#submit-state");
  const successP = submitState.querySelector('[data-state="success"]');
  const errorP = submitState.querySelector('[data-state="error"]');
  const backBtn = document.getElementById("back-to-form");
  const submitBtn = form.querySelector('[type="submit"]');

  // Pagalbinės - show ir hide
  const show = (...items) =>
    items.forEach((item) => item && (item.hidden = false));
  const hide = (...items) =>
    items.forEach((item) => item && (item.hidden = true));

  let lockedScale = null; // kad nemirsketu keičian dydį

  function resetView() {
    show(form, waves, agent);
    hide(submitState, successP, errorP);
    submitBtn.disabled = false;
    submitBtn.textContent = "REGISTRUOTIS";
    form.reset();
    lockedScale = null;
  }

  // Modalo dyzio pritaikymas
  function fitModalToViewport() {
    // Dydžio užrakinimas kad nekistų
    if (lockedScale !== null) {
      dialog.style.setProperty("--modal-scale", String(lockedScale));
      return;
    }
    dialog.style.setProperty("--modal-scale", "1");

    //ekrano apskaičiavimai modalo dydžiui
    const vw = document.documentElement.clientWidth;
    const vh = window.innerHeight;
    const margin = 12;

    const naturalW = dialog.scrollWidth;
    const naturalH = dialog.scrollHeight;

    const maxW = Math.max(1, vw - margin * 2);
    const maxH = Math.max(1, vh - margin * 2);

    const scaleW = maxW / naturalW;
    const scaleH = maxH / naturalH;

    const scale = Math.max(0.5, Math.min(1, Math.min(scaleW, scaleH)));
    dialog.style.setProperty("--modal-scale", String(scale));
  }

  //Modalo atidarymo logika
  function openModal() {
    modal.hidden = false;
    document.body.classList.add("no-scroll", "modal-open");

    dialog.style.animation = "none";
    void dialog.offsetWidth;

    fitModalToViewport();
    dialog.style.animation = "modalOpen 0.25s ease-out forwards";

    window.addEventListener("resize", fitModalToViewport);
    window.addEventListener("orientationchange", fitModalToViewport);
  }

  //Modalo uždarymo logika
  function closeModal() {
    dialog.style.animation = "modalClose 0.2s ease-in forwards";
    setTimeout(() => {
      modal.hidden = true;
      document.body.classList.remove("no-scroll", "modal-open");
      dialog.style.animation = "";
      dialog.style.removeProperty("--modal-scale");
      resetView();
      window.removeEventListener("resize", fitModalToViewport);
      window.removeEventListener("orientationchange", fitModalToViewport);
    }, 200);
  }

  // Event'ai
  openBtn.addEventListener("click", openModal);
  backdrop.addEventListener("click", closeModal);
  closeBtns.forEach((btn) => btn.addEventListener("click", closeModal));

  // formos submit logika
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // Užrakinam dabartinį scale, kad nekeistųsi dydis
    const currentScale =
      getComputedStyle(dialog).getPropertyValue("--modal-scale") || "1";
    lockedScale = parseFloat(currentScale);

    submitBtn.disabled = true;
    submitBtn.textContent = "Siunčiama…";

    hide(form, waves, agent);
    show(submitState);
    show(successP);
    hide(errorP);

    try {
      const res = await sendForm();
      if (!res.ok) throw new Error("fail");
    } catch {
      hide(successP);
      show(errorP);
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = "REGISTRUOTIS";
    }
  });

  // atgal mygtukas po formos išsiuntimo
  if (backBtn) {
    backBtn.addEventListener("click", () => {
      resetView();
      fitModalToViewport();
    });
  }

  const API_BASE =
    location.hostname.includes("localhost") ||
    location.hostname.includes("127.0.0.1")
      ? "http://localhost:8000" // PHP built-in serveris lokaliai
      : "https://BACK-END-DOMENAS"; // čia idėti back-end domeną

  async function sendForm() {
    const response = await fetch(`${API_BASE}/public/register.php`, {
      method: "POST",
      body: new FormData(form),
    });
    // PHP grąžina { ok: true/false, ... }
    let data = {};
    try {
      data = await response.json();
    } catch {}
    return { ok: response.ok && data.ok !== false, data };
  }
});
