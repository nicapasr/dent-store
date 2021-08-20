<template>
  <div>
    <p class="error">{{ error }}</p>

    <div class="d-flex justify-content-center">
      <div class="col-12 col-sm-10 col-xl-6">
        <qrcode-stream @decode="onDecode" @init="onInit" />
      </div>
    </div>
    <hr />
    <stock-in-modal-component :ware_house="this.ware_house" :material="this.material" />
  </div>
</template>

<script>
import { QrcodeStream } from "vue-qrcode-reader";
import axios from "axios";

export default {
  props: ["ware_house"],
  components: { QrcodeStream },

  data() {
    return {
      material: {},
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
      if (str[0] != "" && str[1] == "materialstore@kku") {
        axios
          .get("/admin/qrcode/scan/material/" + str[0])
          .then((response) => {
            if (response.data.status == 200) {
              this.material = response.data.body;
              $("#qrcode_stock_in_modal").modal("show");
            }
          })
          .catch((error) => {
            Swal.fire({
              title: "พบข้อผิดพลาด",
              text: "ไม่พบวัสดุ หรือ รูปแบบ QRCode ไม่ถูกต้อง",
              icon: "error",
              showConfirmButton: true,
            });
          });
      }
    },
  },
};
</script>
