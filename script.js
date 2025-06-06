function addProducto(id, token) {
  let url = "clases/carrito.php";
  let formData = new FormData();

  formData.append("id", id);
  formData.append("token", token);

  // enviamos los datos mediante post
  fetch(url, {
    method: "POST",
    body: formData,
    mode: "cors",
  }).then((response) => response.json());
}
