<template>
  <ValidationObserver v-slot="{ handleSubmit, reset }">
    <!-- User Modal -->
    <div
      class="modal fade"
      id="qrcode_stock_in_modal"
      tabindex="-1"
      role="dialog"
    >
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">นำเข้าวัสดุ</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            ref="form"
            @submit.prevent="handleSubmit(onSubmit)"
            @reset.prevent="reset"
          >
            <div class="modal-body pt-0 pb-0" id="attachment-body-content">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="input_code">รหัสวัสดุ</label>
                    <input
                      type="text"
                      class="form-control"
                      :value="material.m_code"
                      readonly
                    />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="input_name">ชื่อวัสดุ</label>
                    <input
                      type="text"
                      class="form-control"
                      :value="material.m_name"
                      readonly
                    />
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="warehouses">นำเข้าจาก:</label>
                    <ValidationProvider
                      name="warehouses"
                      rules="required"
                      :bails="false"
                      v-slot="{ classes, errors }"
                    >
                      <div class="control" :class="classes">
                        <select class="form-control" v-model="warehouse">
                          <option value="" disabled selected>
                            เลือกคลังที่เบิกมา
                          </option>
                          <option
                            v-for="item in ware_house"
                            :key="item.id_warehouse"
                            :value="item.id_warehouse"
                          >
                            {{ item.warehouse_name }}
                          </option>
                        </select>
                        <span>{{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="row" v-if="material.m_exp_date === 1">
                <div class="col">
                  <div class="form-group">
                    <label for="input_code">วันหมดอายุ</label>
                    <ValidationProvider
                      name="date_exp"
                      rules="required"
                      :bails="false"
                      v-slot="{ classes, errors }"
                    >
                      <div class="control" :class="classes">
                        <datePicker
                          v-model="date_exp"
                          :config="options"
                        ></datePicker>
                        <span>{{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="input_value">จำนวนนำเข้า</label>
                    <ValidationProvider
                      name="value"
                      rules="required|numeric"
                      :bails="false"
                      v-slot="{ classes, errors }"
                    >
                      <div class="control" :class="classes">
                        <input
                          type="text"
                          class="form-control"
                          v-model="value"
                        />
                        <span>{{ errors[0] }}</span>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">บันทึก</button>
              <button type="reset" class="btn btn-danger" data-dismiss="modal">
                ยกเลิก
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /User Modal -->
  </ValidationObserver>
</template>

<script>
import { extend } from "vee-validate";
import { required, numeric } from "vee-validate/dist/rules";
import datePicker from "vue-bootstrap-datetimepicker";
import "pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css";
// import "../../../public/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js";

extend("numeric", {
  ...numeric,
  message: "กรุณากรอกข้อมูลเป็นตัวเลขจำนวนเต็ม",
});

extend("required", {
  ...required,
  message: "กรุณากรอกข้อมูล",
});

export default {
  props: ["ware_house", "material"],

  components: {
    datePicker,
  },

  data() {
    return {
      date_exp: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      value: "",
      warehouse: "",
      error: "",
    };
  },

  methods: {
    onSubmit() {
      const code = this.material.m_code;
      const name = this.material.m_name;
      const date_exp = this.date_exp;
      const warehouse = this.warehouse;
      const value = this.value;
      const _this = this;

      if (typeof code === "undefined" || code === null || code === "") {
        Swal.fire({
          title: "พบข้อผิดพลาด",
          text: "ไม่พบวัสดุที่ต้องการเพิ่ม",
          icon: "error",
          showConfirmButton: true,
        }).then(function (confirm) {
          if (confirm.value) {
            return;
          }
        });
      } else {
        Swal.fire({
          title: "กรุณาตรวจสอบข้อมูล!",
          text:
            "คุณต้องการเพิ่มวัสดุ " + name + " จำนวน " + value + " ใช่หรือไม่?",
          icon: "warning",
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonColor: "#f5365c",
        }).then(function (confirm) {
          if (!confirm.value) {
            return;
          }

          if (date_exp != null && date_exp != "") {
            _this.updateMarterialExp({
              m_code: code,
              m_in: value,
              warehouse: warehouse,
              date_exp: date_exp,
            });
          } else {
            _this.updateMarterial({
              m_code: code,
              m_in: value,
              warehouse: warehouse,
            });
          }
        });
      }
    },

    updateMarterialExp: function(params) {
      const formData = params;
      const { _name, _value } = params;

      axios
        .put("/admin/qrcode/scan/in/exp", formData)
        .then((response) => {
          console.log(response);
          const jsonData = JSON.parse(response.data);
          if (jsonData.HEADER.status === 200) {
            Swal.fire({
              title: "สำเร็จ",
              text:
                "เพิ่มวัสดุ " + _name + " จำนวน " + _value + " เรียบร้อยแล้ว",
              icon: "success",
              showConfirmButton: true,
            }).then((confirm) => {
              if (!confirm.value) {
                return;
              }

              window.location.reload();
            });
          }
        })
        .catch((error) => {
          console.error(error.response);
        });
    },

    updateMarterial: function(params) {
      const formData = params;
      const { _name, _value } = params;

      axios
        .put("/admin/qrcode/scan/in", formData)
        .then((response) => {
          console.log(response);
          const jsonData = JSON.parse(response.data);
          if (jsonData.HEADER.status === 200) {
            Swal.fire({
              title: "สำเร็จ",
              text:
                "เพิ่มวัสดุ " + _name + " จำนวน " + _value + " เรียบร้อยแล้ว",
              icon: "success",
              showConfirmButton: true,
            }).then((confirm) => {
              if (!confirm.value) {
                return;
              }

              window.location.reload();
            });
          }
        })
        .catch((error) => {
          console.error(error.response);
        });
    },
  },
};
</script>
