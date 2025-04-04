<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <style>
    /* -------------------------------------------
       General / Body / Layout
       ------------------------------------------- */
    @import url('https://static.jocarsa.com/fuentes/ubuntu-font-family-0.83/ubuntu.css');

   html, body {
  height: 100vh; /* Full viewport height */
  margin: 0;
  padding: 0;
  font-family:Ubuntu,sans-serif
}
    body {
  display: flex;
  flex-direction: column;
}


    header, footer {
  flex: 0 0 auto;
}

    main {
  flex: 1 1 auto;      /* Fill the remaining space between header and footer */
  display: flex;
  overflow: hidden;    /* Hide overflow so child panels can scroll internally */
}
    /* -------------------------------------------
       Header
       ------------------------------------------- */
    header {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      background:darkslategray;
    }

    header h1 {
      margin: 0;
      padding: 10px;
      font-size: 20px;
      display: flex;
      flex-direction: row;
      align-items: center;
      color: white;
    }

    header h1 img {
      width: 35px;
      margin-right: 16px;
    }

    header nav {
      display: flex;
      flex-direction: row;
      gap: 2px;
    }

    header .boton {
      border: 0;
      background: white;
      width: 30px;
      height: 30px;
      cursor: pointer;
    }

    header .boton:first-child {
      border-radius: 5px 0 0 5px;
    }

    header .boton:last-child {
      border-radius: 0 5px 5px 0;
    }

    #cerrarsesion {
      filter: brightness(14.5);
      cursor: pointer;
      padding: 0 10px;
    }

    .icono {
      filter: brightness(0);
    }

    /* -------------------------------------------
       Main layout: Left nav, center forms, right preview
       ------------------------------------------- */

    /* Left nav */
    main nav {
  width: 200px;
  background: DarkSlateGray;
  color: white;
  padding: 10px;
  overflow-y: auto;
}

    main nav .enlaces > div {
      padding: 10px;
      margin-bottom: 5px;
      border-radius: 5px;
      cursor: pointer;
      background: rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
    }

    main nav .enlaces > div:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    main nav .icono {
      background: white;
      width: 30px;
      height: 30px;
      display: inline-block;
      border-radius: 20px;
      color: DarkSlateGray;
      text-align: center;
      font-weight: bold;
      margin-right: 10px;
      line-height: 30px;
      filter: none;
    }

    /* Center panel for forms */
/* Center panel for forms */
#form-panel {
  flex: 1;                   /* Take available horizontal space */
  background: white;
  color: black;
  border-radius: 10px;
  padding: 20px;
  overflow-y: auto;          /* Enable vertical scrolling */
  position: relative;
  z-index: 1;
  border-right: 1px solid lightgrey;
}

/* Right side: iframe preview */
#preview-panel {
  width: 950px;
  background: #ddd;
  margin-left: 10px;
  overflow-y: auto;          /* Enable vertical scrolling */
  position: relative;
  z-index: 1;
  /* Note: Removed min-width to better fit the layout */
}

    #preview-panel iframe {
      width: 100%;
      height: 100%;
      border: none;
    }

    /* Smaller utility styles for forms */
    .form-group {
      margin-bottom: 10px;
    }
    label {
      display: block;
      font-weight: bold;
      margin-bottom: 4px;
    }
    input[type="text"],
    textarea {
      width: 100%;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font: inherit;
      box-sizing:border-box;
    }
    .array-item {
      background: white;
      padding: 20px 0px;
      margin-bottom: 10px;
      border-bottom:1px solid lightgrey;
    }
    .delete-item {
      background: white;
      color: white;
      border: none;
      padding: 5px 8px;
      cursor: pointer;
      margin-top: 5px;
      border-radius: 4px;
      box-shadow:0px 2px 0px red;
      border:1px solid red;
      margin:5px;
    }
    .add-button {
      background: white;
      border:1px solid green;
      box-shadow:0px 2px 0px green;
      color: white;
      padding: 6px 12px;
      cursor: pointer;
      margin-top: 10px;
      border-radius: 4px;
      margin:5px;
    }
    .move-up,.move-down {
      background: white;
      border:1px solid orange;
      box-shadow:0px 2px 0px orange;
      color: orange;
      padding: 6px 12px;
      cursor: pointer;
      margin-top: 10px;
      border-radius: 4px;
      margin:5px;
    }
    button{
      width:50px;
    }

    /* Additional styling for the photo upload form */
    .photo-preview {
      margin: 10px 0;
    }
    .photo-preview img {
      display: block;
      max-width: 150px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    @media print {
  body * {
    visibility: hidden;
  }
  #preview-panel, #preview-panel * {
    visibility: visible;
  }
  #preview-panel {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }
}
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <h1>
      <img src="https://static.jocarsa.com/logos/teal.png" alt="Logo">
      jocarsa | aplicación
    </h1>
    <nav>
      <button class="boton" id="invertir"><span class="icono">☀</span></button>
      <button class="boton" id="textogrande"><span class="icono">🔎</span></button>
      <button class="boton" id="textopequeno"><span class="icono">🔎</span></button>
    </nav>
    <div id="cerrarsesion">🔒</div>
  </header>

  <script>
    let tamanioinicial = 1;
    let invertido = false;
    document.querySelector("#cerrarsesion").onclick = function(){
      window.location = "logout.php";
    }
    document.querySelector("#textogrande").onclick = function(){
      tamanioinicial *= 1.1;
      document.querySelector("body").style.fontSize = tamanioinicial + "em";
    }
    document.querySelector("#textopequeno").onclick = function(){
      tamanioinicial *= 0.9;
      document.querySelector("body").style.fontSize = tamanioinicial + "em";
    }

    document.querySelector("#invertir").onclick = function(){
      if(invertido){
        document.querySelector("html").style.filter = "invert(0) hue-rotate(0deg)";
        invertido = false;
      } else {
        document.querySelector("html").style.filter = "invert(1) hue-rotate(150deg)";
        invertido = true;
      }
    }
  </script>

  <!-- Main content: 3 columns -->
  <main>
    <!-- Left side nav -->
    <nav>
      <div class="enlaces">
        <div data-section="nombre"><span class="icono">N</span><span>Nombre</span></div>
        <div data-section="telefono"><span class="icono">T</span><span>Teléfono</span></div>
        <div data-section="email"><span class="icono">E</span><span>Email</span></div>
        <div data-section="titulo"><span class="icono">T</span><span>Título</span></div>
        <div data-section="sobre_mi"><span class="icono">S</span><span>Sobre mí</span></div>
        <div data-section="foto"><span class="icono">F</span><span>Foto</span></div> <!-- NEW -->
        <div data-section="habilidades"><span class="icono">H</span><span>Habilidades</span></div>
        <div data-section="idiomas"><span class="icono">I</span><span>Idiomas</span></div>
        <div data-section="certificaciones"><span class="icono">C</span><span>Certificaciones</span></div>
        <div data-section="redes_sociales"><span class="icono">R</span><span>Redes Sociales</span></div>
        <div data-section="experiencia_profesional"><span class="icono">E</span><span>Experiencia</span></div>
        <div data-section="formacion"><span class="icono">F</span><span>Formación</span></div>
        <div data-section="publicaciones"><span class="icono">P</span><span>Publicaciones</span></div>
      	<div id="print-action" style="cursor:pointer; padding:10px; border-radius:5px; background: rgba(255,255,255,0.1); margin-top:10px;">
      <span class="icono">🖨️</span><span>Imprimir</span>
      <script>
      	document.getElementById("print-action").addEventListener("click", function() {
			  // This will trigger the print dialog for the content inside the iframe
			  document.getElementById("preview").contentWindow.print();
			});
      </script>
    </div>
      </div>
    </nav>

    <!-- Center panel for input forms -->
    <section id="form-panel">
      <h2>Edición de datos</h2>
      <div id="dynamic-form"></div>
    </section>

    <!-- Right side with iframe preview -->
    <section id="preview-panel">
      <iframe id="preview" src="cv.php"></iframe>
    </section>
  </main>

  <footer>
    Pié de página
  </footer>

  <script>
    /* -------------------------------------------------
       Global data storage
       ------------------------------------------------- */
    let CVdata = {}; // We'll store the entire JSON object here.

    // 1. On load, fetch the data from api/getData.php
    fetch("api/getData.php")
      .then(res => res.json())
      .then(data => {
        CVdata = data;
      })
      .catch(err => console.error("Error loading data:", err));

    // 2. Listen for clicks in the left nav: show a form for that section
    document.querySelectorAll("nav .enlaces > div[data-section]").forEach(item => {
      item.addEventListener("click", () => {
        let sectionName = item.getAttribute("data-section");
        showFormFor(sectionName);
      });
    });

    // 3. Dynamically build forms
    function showFormFor(sectionName) {
      const container = document.getElementById("dynamic-form");
      container.innerHTML = ""; // Clear previous form

      // Single-field sections
      if (["nombre", "telefono", "email", "titulo"].includes(sectionName)) {
        buildSingleFieldForm(sectionName, container);
      }
      // Single-textarea section
      else if (sectionName === "sobre_mi") {
        buildSingleTextareaForm(sectionName, container);
      }
      // Custom photo upload
      else if (sectionName === "foto") {
        buildPhotoUploadForm(sectionName, container);
      }
      // Arrays
      else if ([
        "habilidades",
        "idiomas",
        "certificaciones",
        "redes_sociales",
        "experiencia_profesional",
        "formacion",
        "publicaciones"
      ].includes(sectionName)) {
        buildArrayForm(sectionName, container);
      }
    }

    function buildSingleFieldForm(sectionName, container) {
      // label + input
      let formGroup = document.createElement("div");
      formGroup.className = "form-group";

      let labelEl = document.createElement("label");
      labelEl.textContent = sectionName.charAt(0).toUpperCase() + sectionName.slice(1);
      formGroup.appendChild(labelEl);

      let inputEl = document.createElement("input");
      inputEl.type = "text";
      inputEl.value = CVdata[sectionName] || "";
      inputEl.onchange = () => {
        CVdata[sectionName] = inputEl.value;
        saveData();
      };
      formGroup.appendChild(inputEl);

      container.appendChild(formGroup);
    }

    function buildSingleTextareaForm(sectionName, container) {
      // label + textarea
      let formGroup = document.createElement("div");
      formGroup.className = "form-group";

      let labelEl = document.createElement("label");
      labelEl.textContent = "Sobre mí";
      formGroup.appendChild(labelEl);

      let textareaEl = document.createElement("textarea");
      textareaEl.rows = 6;
      textareaEl.value = CVdata[sectionName] || "";
      textareaEl.onchange = () => {
        CVdata[sectionName] = textareaEl.value;
        saveData();
      };
      formGroup.appendChild(textareaEl);

      container.appendChild(formGroup);
    }

    // NEW: Build a photo upload field, which will store base64 in CVdata["foto"]
    function buildPhotoUploadForm(sectionName, container) {
      // Current Photo Preview
      let currentPhotoDiv = document.createElement("div");
      currentPhotoDiv.className = "photo-preview";
      let currentPhotoImg = document.createElement("img");
      currentPhotoImg.src = CVdata.foto 
        ? CVdata.foto 
        : "https://static.jocarsa.com/fotos/jose_vicente_carratala_curriculum.jpg";
      currentPhotoDiv.appendChild(currentPhotoImg);
      container.appendChild(currentPhotoDiv);

      // Label for the file input
      let labelEl = document.createElement("label");
      labelEl.textContent = "Subir nueva foto (se guardará en Base64):";
      container.appendChild(labelEl);

      // File input
      let inputEl = document.createElement("input");
      inputEl.type = "file";
      inputEl.accept = "image/*";
      inputEl.onchange = (e) => {
        let file = e.target.files[0];
        if (!file) return;

        let reader = new FileReader();
        reader.onload = function(evt) {
          // evt.target.result is the base64 string
          CVdata.foto = evt.target.result;
          saveData();
        };
        reader.readAsDataURL(file);
      };
      container.appendChild(inputEl);
    }

    function buildArrayForm(sectionName, container) {
  // If the section does not exist or is empty, initialize it with one default element.
  if (!CVdata[sectionName] || CVdata[sectionName].length === 0) {
    let defaultItem = {};
    if (sectionName === "habilidades" || sectionName === "idiomas" || sectionName === "certificaciones") {
      defaultItem = { nombre: "", nivel: 1 };
    } else if (sectionName === "redes_sociales") {
      defaultItem = { nombre: "", url: "", icono: "" };
    } else if (sectionName === "experiencia_profesional") {
      defaultItem = { puesto: "", empresa: "", logo: "", fecha: "", descripcion: "" };
    } else if (sectionName === "formacion") {
      defaultItem = { titulo: "", institucion: "", logo: "", fecha: "", descripcion: "" };
    } else if (sectionName === "publicaciones") {
      defaultItem = { titulo: "", fecha: "", descripcion: "" };
    }
    CVdata[sectionName] = [ defaultItem ];
  }

  // Get the array from CVdata now that it is guaranteed to exist.
  let items = CVdata[sectionName];
  // Clear previous form content
  container.innerHTML = "";

  // Render each item.
  items.forEach((item, index) => {
    renderArrayItem(sectionName, container, item, index);
  });

  // "Add new" button to allow adding more items.
  let addBtn = document.createElement("button");
  addBtn.className = "add-button";
  addBtn.textContent = "➕";
  addBtn.onclick = () => {
    let newItem = {};
    if (sectionName === "habilidades" || sectionName === "idiomas" || sectionName === "certificaciones") {
      newItem = { nombre: "", nivel: 1 };
    } else if (sectionName === "redes_sociales") {
      newItem = { nombre: "", url: "", icono: "" };
    } else if (sectionName === "experiencia_profesional") {
      newItem = { puesto: "", empresa: "", logo: "", fecha: "", descripcion: "" };
    } else if (sectionName === "formacion") {
      newItem = { titulo: "", institucion: "", logo: "", fecha: "", descripcion: "" };
    } else if (sectionName === "publicaciones") {
      newItem = { titulo: "", fecha: "", descripcion: "" };
    }
    CVdata[sectionName].push(newItem);
    saveData();
    showFormFor(sectionName); // Re-render the form after adding a new element.
  };
  container.appendChild(addBtn);
}


    function renderArrayItem(sectionName, container, item, index) {
  let wrapper = document.createElement("div");
  wrapper.className = "array-item";

  Object.keys(item).forEach(key => {
    let formGroup = document.createElement("div");
    formGroup.className = "form-group";

    let labelEl = document.createElement("label");
    labelEl.textContent = key.charAt(0).toUpperCase() + key.slice(1);
    formGroup.appendChild(labelEl);

    // For work experience and education, use file input for "logo"
    if ((sectionName === "experiencia_profesional" || sectionName === "formacion") && key === "logo") {
      // Create an image preview if one exists
      let previewImg = document.createElement("img");
      previewImg.style.maxWidth = "100px";
      previewImg.style.display = "block";
      previewImg.style.marginBottom = "10px";
      if (item[key]) {
        previewImg.src = item[key];
      }
      formGroup.appendChild(previewImg);

      // Create file input for uploading new image
      let inputEl = document.createElement("input");
      inputEl.type = "file";
      inputEl.accept = "image/*";
      inputEl.onchange = (e) => {
        let file = e.target.files[0];
        if (!file) return;
        let reader = new FileReader();
        reader.onload = function(evt) {
          // Save the base64 image in the CVdata array
          CVdata[sectionName][index][key] = evt.target.result;
          saveData();
          // Update the preview
          previewImg.src = evt.target.result;
        };
        reader.readAsDataURL(file);
      };
      formGroup.appendChild(inputEl);
    } else {
      // Default: use text input for other fields
      let inputEl = document.createElement("input");
      inputEl.type = "text";
      inputEl.value = item[key] || "";
      inputEl.onchange = () => {
        CVdata[sectionName][index][key] = inputEl.value;
        saveData();
      };
      formGroup.appendChild(inputEl);
    }

    wrapper.appendChild(formGroup);
  });

  // Move Up button
  let upBtn = document.createElement("button");
  upBtn.className = "move-up";
  upBtn.textContent = "⬆";
  upBtn.onclick = () => {
    if (index > 0) {
      [CVdata[sectionName][index - 1], CVdata[sectionName][index]] = [CVdata[sectionName][index], CVdata[sectionName][index - 1]];
      saveData();
      showFormFor(sectionName);
    }
  };
  wrapper.appendChild(upBtn);

  // Move Down button
  let downBtn = document.createElement("button");
  downBtn.className = "move-down";
  downBtn.textContent = "⬇";
  downBtn.onclick = () => {
    if (index < CVdata[sectionName].length - 1) {
      [CVdata[sectionName][index + 1], CVdata[sectionName][index]] = [CVdata[sectionName][index], CVdata[sectionName][index + 1]];
      saveData();
      showFormFor(sectionName);
    }
  };
  wrapper.appendChild(downBtn);

  // Delete button
  let delBtn = document.createElement("button");
  delBtn.className = "delete-item";
  delBtn.textContent = "❌";
  delBtn.onclick = () => {
    CVdata[sectionName].splice(index, 1);
    saveData();
    showFormFor(sectionName);
  };
  wrapper.appendChild(delBtn);

  container.appendChild(wrapper);
}



    // 4. Save data to the server (api/save.php)
    function saveData() {
      fetch("api/save.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(CVdata)
      })
      .then(res => res.json())
      .then(response => {
        // After successful save, refresh the iframe
        document.getElementById("preview").contentWindow.location.reload();
      })
      .catch(err => console.error("Error saving data:", err));
    }
  </script>
</body>
</html>
