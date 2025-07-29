import Swal from 'sweetalert2'

export function swalConfirm({
  title = 'Yakin?',
  text = '',
  icon = 'warning',
  confirmButtonText = 'Ya',
  cancelButtonText = 'Batal',
  confirmButtonColor = '#3085d6',
  cancelButtonColor = '#d33',
  ...rest
} = {}) {
  return Swal.fire({
    title,
    text,
    icon,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    confirmButtonColor,
    cancelButtonColor,
    ...rest
  })
}

export function swalSuccess({
  title = 'Berhasil!',
  text = '',
  timer = 2000,
  ...rest
} = {}) {
  return Swal.fire({
    icon: 'success',
    title,
    text,
    timer,
    showConfirmButton: false,
    ...rest
  })
}

export function swalError({
  title = 'Gagal!',
  text = '',
  timer = 2000,
  ...rest
} = {}) {
  return Swal.fire({
    icon: 'error',
    title,
    text,
    timer,
    showConfirmButton: false,
    ...rest
  })
}

export function swalInfo({
  title = 'Info',
  text = '',
  timer = 2000,
  ...rest
} = {}) {
  return Swal.fire({
    icon: 'info',
    title,
    text,
    timer,
    showConfirmButton: false,
    ...rest
  })
}

export function swalPromptSelect({
  title = 'Pilih',
  inputOptions = {},
  inputPlaceholder = '',
  confirmButtonText = 'OK',
  cancelButtonText = 'Batal',
  ...rest
} = {}) {
  return Swal.fire({
    title,
    input: 'select',
    inputOptions,
    inputPlaceholder,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    ...rest
  })
}
