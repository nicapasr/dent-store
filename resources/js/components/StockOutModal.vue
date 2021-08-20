<template>
  <!-- modal -->
  <div
    class="modal fade"
    id="widthdraw-modal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="edit-modal-label"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered vw-50" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-modal-label">ข้อมูลเบิกวัสดุ</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0" id="attachment-body-content">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
              <div class="form-group" v-if="stockOut.materials.m_exp_date">
                <label for="input_date_exp">วันหมดอายุ</label>
                <input
                  type="text"
                  id="input_date_exp"
                  class="form-control"
                  name="exp_date"
                  readonly
                  :value="convertToThai(stockOut.b_exp_date)"
                />
              </div>

              <div class="form-group">
                <label for="input_m_code">รหัสวัสดุ</label>
                <input
                  type="text"
                  id="input_m_code"
                  class="form-control"
                  name="m_code"
                  readonly
                  :value="stockOut.materials.m_code"
                />
              </div>

              <div class="form-group">
                <label for="input_material">วัสดุ</label>
                <input
                  type="text"
                  id="input_material"
                  class="form-control"
                  name="m_name"
                  readonly
                  :value="stockOut.materials.m_name"
                />
              </div>

              <div class="form-group">
                <label for="input_balance">คงเหลือ</label>
                <input
                  type="text"
                  id="input_balance"
                  class="form-control"
                  readonly
                  :value="stockOut.b_value"
                />
              </div>

              <!-- <ValidationProvider
                  name="ความเร่งด่วน"
                  rules="required"
                  :bails="false"
                  v-slot="{ classes, errors }"
                >
                  <div class="control" :class="classes">
                    <div class="form-group">
                      <label for="important" class="text-warning"
                        >ความเร่งด่วน</label
                      >
                      <select
                        class="form-control"
                        id="important"
                        v-model="important"
                      >
                        <option value="" disabled selected>
                          กรุณาเลือกความเร่งด่วน
                        </option>
                        <option
                          v-for="item in importants"
                          :key="item.id_imp"
                          :value="item.id_imp"
                        >
                          {{ item.imp_name }}
                        </option>
                      </select>
                      <span>{{ errors[0] }}</span>
                    </div>
                  </div>
                </ValidationProvider> -->

              <ValidationProvider
                name="ผู้ขอเบิก"
                rules="required"
                :bails="false"
                v-slot="{ classes, errors }"
              >
                <div class="control" :class="classes">
                  <div class="form-group">
                    <label for="input_member">ผู้ขอเบิก</label>
                    <AutoSearch v-model="member" :data="members" />
                    <!-- <input
                      type="text"
                      class="form-control"
                      id="input_member"
                      min="1"
                    /> -->
                    <span>{{ errors[0] }}</span>
                  </div>
                </div>
              </ValidationProvider>

              <ValidationProvider
                name="ห้อง"
                rules="required|numeric"
                :bails="false"
                v-slot="{ classes, errors }"
              >
                <div class="control" :class="classes">
                  <div class="form-group">
                    <label for="room">ห้อง</label>
                    <input
                      type="number"
                      class="form-control"
                      id="room"
                      min="1"
                      v-model="room"
                    />
                    <span>{{ errors[0] }}</span>
                  </div>
                </div>
              </ValidationProvider>

              <ValidationProvider
                name="จำนวนที่ต้องการเบิก"
                rules="required|numeric|min_value:1"
                :bails="false"
                v-slot="{ classes, errors }"
              >
                <div class="control" :class="classes">
                  <div class="form-group">
                    <label for="value">จำนวนที่ต้องการเบิก</label>
                    <input
                      type="text"
                      class="form-control"
                      id="value"
                      v-model="value"
                    />
                    <span>{{ errors[0] }}</span>
                  </div>
                </div>
              </ValidationProvider>

              <div class="row">
                <div class="col"></div>
                <div class="col-auto">
                  <button type="submit" class="btn btn-success">บันทึก</button>
                  <button
                    type="reset"
                    data-dismiss="modal"
                    class="btn btn-danger"
                  >
                    ยกเลิก
                  </button>
                </div>
              </div>
            </form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AutoSearch from "vue-bootstrap-typeahead";
import axios from "axios";
import { extend } from "vee-validate";
import { required, numeric, min_value } from "vee-validate/dist/rules";

extend("numeric", {
  ...numeric,
  message: "กรุณากรอกข้อมูลเป็นตัวเลขจำนวนเต็ม",
});

extend("required", {
  ...required,
  message: "กรุณากรอกข้อมูล",
});

extend("min_value", {
  ...min_value,
  message: "กรุณากรอกข้อมูลมากกว่าหรือเท่ากับ 1",
});

export default {
  props: ["stockOut"],

  components: {
    AutoSearch,
  },

  created() {
    this.getMembers();
    this.balance = this.stockOut.b_value;
  },

  data() {
    return {
      member: null,
      members: [],
      balance: null,
      room: "",
      value: "",
      error: "",
    };
  },

  methods: {
    getMembers() {
      axios
        .get("/admin/qrcode/scan/out/members")
        .then((response) => {
          const jsonData = JSON.parse(response.data);
          if (jsonData.HEADER.status === 200) {
            this.members = jsonData.BODY;
          }
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    onSubmit() {
      const id = this.stockOut.id;
      const member = this.member;
      const room = this.room;
      const value = this.value;
      const expDate = this.stockOut.b_exp_date;

      if (value > this.stockOut.b_value) {
        Swal.fire({
          title: "กรุณาตรวจสอบข้อมูล!",
          text: "จำนวนที่ต้องการเบิกต้อง < หรือ = จำนวนคงเหลือ",
          icon: "warning",
          showConfirmButton: true,
        });
        return;
      }

      if (typeof id === "undefined" || id === null || id === "") {
        Swal.fire({
          title: "พบข้อผิดพลาด",
          text: "ไม่พบวัสดุที่ต้องการเบิก",
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
            "คุณต้องการเบิกวัสดุ " + name + " จำนวน " + value + " ใช่หรือไม่?",
          icon: "warning",
          showConfirmButton: true,
          showCancelButton: true,
          cancelButtonColor: "#f5365c",
        }).then(function (confirm) {
          if (!confirm.value) {
            return;
          }

          const formData = {
            id: id,
            member: member,
            room: room,
            width_draw_value: value,
            exp_date: expDate,
          };

          axios
            .put("/admin/qrcode/scan/out", formData)
            .then((response) => {
              const jsonData = JSON.parse(response.data);
              if (jsonData.HEADER.status == 200) {
                Swal.fire({
                  title: "สำเร็จ",
                  text:
                    "เบิกวัสดุ " + name + " จำนวน " + value + " เรียบร้อยแล้ว",
                  icon: "success",
                  showConfirmButton: true,
                }).then(function (confirm) {
                  if (!confirm.value) {
                    return;
                  }

                  location.reload();
                  //   $("#widthdraw-modal").modal("hide");
                  //   $("#get_stock_out").trigger("click");
                });
              }
            })
            .catch((error) => {
              console.error(error.response);
            });
        });
      }
    },

    onChange(text) {
      console.log(text);
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
