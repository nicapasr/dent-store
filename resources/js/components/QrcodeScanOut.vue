<template>
  <div>
    <p class="error">{{ error }}</p>

    <div class="d-flex justify-content-center">
      <div class="col-12 col-sm-10 col-xl-6">
        <qrcode-stream @decode="onDecode" @init="onInit" />
      </div>
    </div>

    <hr />

    <!-- <div class="table-responsive">
      <table class="table align-items-center table-flush text-center">
        <thead class="thead-light">
          <tr>
            <th scope="col">วันที่หมดอายุ</th>
            <th scope="col">รหัสวัสดุ</th>
            <th scope="col">ชื่อวัสดุ</th>
            <th scope="col">จำนวนคงเหลือ</th>
          </tr>
        </thead>
        <tbody class="text-center">
          <tr v-for="item in stockOutsList" :key="item.id_stock_out">
            <td>
              {{ convertToThai(item.b_exp_date) }}
            </td>
            <td>
              {{ item.materials.m_code }}
            </td>
            <td>
              {{ item.materials.m_name }}
            </td>
            <td>
              {{ item.b_value }}
              {{ item.materials.material_unit.unit_name }}
            </td>
          </tr>
        </tbody>
      </table>
    </div> -->
    <stock-out-modal-component :stockOut="materials" />
  </div>
</template>

<script>
import { QrcodeStream } from "vue-qrcode-reader";
import axios from "axios";

export default {
  components: { QrcodeStream },

  mounted() {
    this.convertToThai("2021-08-31");
  },

  data() {
    return {
      materials: {},
      stockOutsList: {},
      room: "",
      value: "",
      error: "",
    };
  },

  methods: {
    async onInit(promise) {
      try {
        await promise;
      } catch (error) {
        if (error.name === "NotAllowedError") {
          this.error = "ERROR: you need to grant camera access permisson";
        } else if (error.name === "NotFoundError") {
          this.error = "ERROR: no camera on this device";
        } else if (error.name === "NotSupportedError") {
          this.error = "ERROR: secure context required (HTTPS, localhost)";
        } else if (error.name === "NotReadableError") {
          this.error = "ERROR: is the camera already in use?";
        } else if (error.name === "OverconstrainedError") {
          this.error = "ERROR: installed cameras are not suitable";
        } else if (error.name === "StreamApiNotSupportedError") {
          this.error = "ERROR: Stream API is not supported in this browser";
        }
      }
    },

    onDecode(result) {
      const str = result.split("|");
      //090501003|materialstore@kku 0 090501003 1 materialstore@kku
      if (str[0] != "" && str[1] == "materialstore@kku") {
        axios
          .get("/admin/qrcode/scan/out/list?m_code=" + str[0])
          .then((response) => {
            const jsonData = JSON.parse(response.data);
            if (jsonData.HEADER.status === 200) {
              const body = jsonData.BODY;
              if (body) {
                // if (
                //   body[0].b_exp_date === null ||
                //   !body[0].materials.m_exp_date
                // ) {
                if (body.b_value > 0) {
                  this.materials = body;
                  $("#widthdraw-modal").modal("show");
                } else {
                  Swal.fire({
                    title: "ขออภัย",
                    text: "วัสดุ " + body.materials.m_name + " หมดสต๊อกแล้ว",
                    icon: "warning",
                    showConfirmButton: true,
                  });
                }
              }
              //   }else {
              //     console.log(body);
              //     this.stockOutsList = body;
              //   }
            }
            // if (response.data.status == 200) {
            //   //   this.value = "";
            //   //   this.warehouse = "";
            //   //   this.$refs.form.reset();
            //   this.material = response.data.data;
            //   if (this.material.m_balance == 0) {
            //     Swal.fire({
            //       title: "พบข้อผิดพลาด",
            //       text: "วัสดุไม่เหลือในคลังแล้ว กรุณาติดต่อเจ้าหน้าที่",
            //       icon: "error",
            //       showConfirmButton: true,
            //     }).then(function (confirm) {
            //       if (confirm.value) {
            //         return;
            //       }
            //     });
            //   } else {
            //     $("#widthdraw-modal").modal("show");
            //   }
            // }
          })
          .catch((error) => {
            console.error(error);
          });
      } else {
        // Swal.fire({
        //   title: "พบข้อผิดพลาด",
        //   text: "รูปแบบ QR Code ไม่ถูกต้อง",
        //   icon: "warning",
        //   showConfirmButton: true,
        // });
      }
    },

    convertToThai(date) {
      const newDate = new Date(date);
      const result = newDate.toLocaleDateString("th-TH", {
        year: "numeric",
        month: "long",
        day: "numeric",
      });

      return result;
    },
  },
};
</script>
