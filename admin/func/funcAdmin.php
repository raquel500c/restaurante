<?php

function usuarioEsAdmin() {
  if ($_SESSION["rol"] == 1) {
    return true;
  } else {
    return false;
  }
}

function usuarioEsTrabajador() {
  if ($_SESSION["rol"] == 2) {
    return true;
  } else {
    return false;
  }
}

function usuarioEsCliente() {
    if ($_SESSION["rol"] == 3) {
      return true;
    } else {
      return false;
    }
}
