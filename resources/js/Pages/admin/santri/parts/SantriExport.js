import ExcelJS from 'exceljs'
import { saveAs } from 'file-saver'

export default async function exportSantriExcel(santriList, options = {}) {
  const workbook = new ExcelJS.Workbook()
  const sheet = workbook.addWorksheet('Data Santri')
  const primary = '2563EB'
  const headerBg = '1E293B'
  const zebra = 'F1F5F9'
  const border = 'E5E7EB'
  const judul = '0F172A'
  const date = new Date()
  const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']
  const metaTanggal = `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`

  // Judul
  sheet.mergeCells('A1:AI1')
  sheet.getCell('A1').value = 'DATA SANTRI BARU PONDOK PESANTREN'
  sheet.getCell('A1').font = { bold: true, size: 18, color: { argb: judul } }
  sheet.getCell('A1').alignment = { horizontal: 'center', vertical: 'middle' }
  sheet.getRow(1).height = 32
  // Metadata
  sheet.mergeCells('A2:AI2')
  sheet.getCell('A2').value = `Diekspor pada: ${metaTanggal}`
  sheet.getCell('A2').font = { size: 11, color: { argb: primary } }
  sheet.getCell('A2').alignment = { horizontal: 'center', vertical: 'middle' }
  sheet.getRow(2).height = 18
  sheet.getRow(3).height = 6
  // Header
  const headers = [
    'No','ID','Nomor Pendaftaran','Nama','NISN','Jenis Kelamin','Tempat Lahir','Tanggal Lahir','Asal Sekolah','Anak Ke','Jumlah Saudara','Kelas','Unit','Status Pembayaran','Status Tes','Nama Ayah','Pekerjaan Ayah','Pekerjaan Ayah Lainnya','Pendidikan Ayah','WA Ayah','Nama Ibu','Pekerjaan Ibu','Pekerjaan Ibu Lainnya','Pendidikan Ibu','WA Ibu','Provinsi','Kabupaten','Kecamatan','Desa','Alamat','Kode Pos','Pasfoto','Akta Lahir','Kartu Keluarga','Ijazah'
  ]
  sheet.addRow(headers)
  const headerRow = sheet.getRow(4)
  headerRow.eachCell(cell => {
    cell.font = { bold: true, color: { argb: 'FFFFFFFF' }, size: 12 }
    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: headerBg } }
    cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true }
    cell.border = {
      top: { style: 'thin', color: { argb: border } },
      bottom: { style: 'thin', color: { argb: border } },
      left: { style: 'thin', color: { argb: border } },
      right: { style: 'thin', color: { argb: border } }
    }
  })
  sheet.getRow(4).height = 28
  // Data
  santriList.forEach((santri, idx) => {
    const row = [
      idx+1,
      santri.id,
      santri.nomor_pendaftaran || '-',
      santri.nama || '-',
      santri.nisn || '-',
      santri.jenis_kelamin || '-',
      santri.tempat_lahir || '-',
      santri.tanggal_lahir || '-',
      santri.asal_sekolah || '-',
      santri.anak_ke || '-',
      santri.jumlah_saudara || '-',
      santri.kelas || '-',
      santri.unit ? santri.unit.toUpperCase() : '-',
      santri.status_pembayaran || '-',
      santri.status_tes || '-',
      santri.orang_tua?.nama_ayah || '-',
      santri.orang_tua?.pekerjaan_ayah || '-',
      santri.orang_tua?.pekerjaan_ayah_lainnya || '-',
      santri.orang_tua?.pendidikan_ayah || '-',
      santri.orang_tua?.wa_ayah || '-',
      santri.orang_tua?.nama_ibu || '-',
      santri.orang_tua?.pekerjaan_ibu || '-',
      santri.orang_tua?.pekerjaan_ibu_lainnya || '-',
      santri.orang_tua?.pendidikan_ibu || '-',
      santri.orang_tua?.wa_ibu || '-',
      santri.orang_tua?.provinsi_id || '-',
      santri.orang_tua?.kabupaten_id || '-',
      santri.orang_tua?.kecamatan_id || '-',
      santri.orang_tua?.desa_id || '-',
      santri.orang_tua?.alamat || '-',
      santri.orang_tua?.kode_pos || '-',
      santri.dokumen?.pasfoto ? 'Lengkap' : 'Belum Lengkap',
      santri.dokumen?.akta_lahir ? 'Lengkap' : 'Belum Lengkap',
      santri.dokumen?.kartu_keluarga ? 'Lengkap' : 'Belum Lengkap',
      santri.dokumen?.ijazah ? 'Lengkap' : 'Belum Lengkap'
    ]
    sheet.addRow(row)
  })
  // Styling data
  for (let i = 5; i <= sheet.rowCount; i++) {
    const row = sheet.getRow(i)
    row.height = 22
    row.eachCell((cell, col) => {
      if (i % 2 === 0) cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: zebra } }
      cell.border = {
        top: { style: 'thin', color: { argb: border } },
        bottom: { style: 'thin', color: { argb: border } },
        left: { style: 'thin', color: { argb: border } },
        right: { style: 'thin', color: { argb: border } }
      }
      if ([1,2,10,11,12,13,14,15].includes(col)) {
        cell.alignment = { horizontal: 'center', vertical: 'middle', wrapText: true }
      } else {
        cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: false }
      }
    })
  }
  // Freeze header & autofilter
  sheet.views = [{ state: 'frozen', ySplit: 4 }]
  sheet.autoFilter = { from: 'A4', to: 'AI4' }
  // Lebar kolom
  const autoCols = [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
  autoCols.forEach(i => {
    sheet.getColumn(i).width = 18
  })
  sheet.getColumn(1).width = 5 // No
  sheet.getColumn(2).width = 8 // ID
  sheet.getColumn(3).width = 35 // Nomor Pendaftaran
  sheet.getColumn(30).width = 40 // Alamat
  for (let i = 5; i <= sheet.rowCount; i++) {
    const cell = sheet.getRow(i).getCell(3)
    cell.alignment = { horizontal: 'left', vertical: 'middle', wrapText: false }
  }
  // Download
  const buffer = await workbook.xlsx.writeBuffer()
  saveAs(new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' }), `Data_Santri_${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear()}.xlsx`)
}
