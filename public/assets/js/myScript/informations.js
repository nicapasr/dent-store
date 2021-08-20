$(function() {
    let selectedTab = localStorage.getItem("selectedTab");
    $(".custom-nev-info")
        .find("a")
        .each(function() {
            if ($(this).text() === selectedTab) {
                let href = $(this)
                    .attr("href")
                    .replace("#", "");
                $(".tab-pane").each(function() {
                    if ($(this).attr("id") == href) {
                        $(this).addClass("show");
                        $(this).addClass("active");
                    }
                });
                $(this).addClass("active");
            }
        });
});

//====================TAB====================
$("#user-tab").on("click", function(e) {
    e.preventDefault();

    setTab($(this));

    window.location.replace("/admin/informations");
});

$("#dep-tab").on("click", function(e) {
    e.preventDefault();

    setTab($(this));

    window.location.replace("/admin/informations");
});

$("#waerhouse-tab").on("click", function(e) {
    e.preventDefault();

    setTab($(this));

    window.location.replace("/admin/informations");
});

$("#unit-tab").on("click", function(e) {
    e.preventDefault();

    setTab($(this));

    window.location.replace("/admin/informations");
});

$("#material-tab").on("click", function(e) {
    e.preventDefault();

    setTab($(this));

    window.location.replace("/admin/informations");
});

function setTab(params) {
    let text = params.find("a").text();
    localStorage.setItem("selectedTab", text);
    $(".custom-nev-info")
        .find("a")
        .each(function() {
            if (params.text() !== text) {
                params.removeClass("active");
            }
        });
}
//====================TAB====================

//Add User
$("#btn_add_user_submit").on("click", function() {
    const _token = $('meta[name="csrf-token"]').attr("content");
    const username = $("#modal-input-userName").val();
    const password = $("#modal-input-password").val();
    const firstName = $("#modal-input-firstName").val();
    const lastName = $("#modal-input-lastName").val();
    const phone = $("#modal-input-phone").val();
    const permission = $("#modal-input-permission").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (!confirm.value) {
            return;
        }

        const formData = {
            username: username,
            password: password,
            firstName: firstName,
            lastName: lastName,
            phone: phone,
            permission: permission,
            _token: _token
        };

        $.post(
            "/admin/informations/user/add",
            formData,
            function(res) {
                const jsonData = JSON.parse(res);
                console.log(jsonData);
                if (jsonData.HEADER.status === 200) {
                    const msg =
                        "เพิ่มบัญชีผู้ใช้งาน " +
                        firstName +
                        " " +
                        lastName +
                        " เรียบร้อยแล้ว";

                    Swal.fire({
                        title: "สำเร็จ",
                        text: msg,
                        icon: "success",
                        showConfirmButton: true,
                        showCancelButton: true,
                        focusConfirm: true,
                        cancelButtonColor: "#f5365c"
                    }).then(function(confirm) {
                        if (!confirm.value) {
                            return;
                        }

                        window.location.reload();
                    });
                } else {
                    const errors = jsonData.HEADER.error_message;
                    Object.keys(errors).forEach(function(key) {
                        var value = errors[key];
                        $(`input[name=${key}]`).addClass("border-danger");
                        $(`select[name=${key}]`).addClass("border-danger");
                    });
                }
            },
            "json"
        );
    });
});

$(document).on("click", "#edit-user", function(e) {
    e.preventDefault();
    var user = $(this).data("user-name");
    console.log(user);
    $("#modal-input-edit-userName").val(user["username"]);
    $("#modal-input-edit-firstName").val(user["first_name"]);
    $("#modal-input-edit-lastName").val(user["last_name"]);
    $("#modal-input-edit-phone").val(user["phone"]);

    $("#modal-input-edit-userName").prop("readonly", true);
    // $("#modal-input-edit-firstName").prop("readonly", true);
    // $("#modal-input-edit-lastName").prop("readonly", true);
    // $("#modal-input-edit-phone").prop("readonly", true);
});

$("#btn_edit_user_submit").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var userName = $("#modal-input-edit-userName").val();
    var firstName = $("#modal-input-edit-firstName").val();
    var lastName = $("#modal-input-edit-lastName").val();
    var phone = $("#modal-input-edit-phone").val();
    var permissions = $("#modal-input-edit-permission").val();
    var permission = parseInt(permissions);

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (!confirm.value) {
            return;
        }

        var formData = {
            username: userName,
            first_name: firstName,
            last_name: lastName,
            phone: phone,
            permission: permission,
            _token: _token
        };

        $.ajax({
            type: "POST",
            url: "/admin/informations/user/edit",
            data: formData,
            dataType: "json",
            success: function(res) {
                console.log(res);
                if (res.status == 0) {
                    Swal.fire({
                        title: "สำเร็จ",
                        text: res.massage,
                        icon: "success",
                        showConfirmButton: true,
                        focusConfirm: true
                    }).then(function(confirm) {
                        if (confirm) {
                            location.reload();
                        }

                        $("#user-form").trigger("reset");
                        $("#add-user-modal").modal("hide");
                    });
                } else {
                    if (res.status == 1) {
                        const errors = res.data;
                        Object.keys(errors).forEach(function(key) {
                            var value = errors[key];
                            $(`input[name=${key}]`).addClass("border-danger");
                        });
                    } else if (res.status == 2) {
                        Swal.fire({
                            title: "พบผิดพลาด",
                            text: res.massage,
                            icon: "error",
                            showConfirmButton: true
                        });
                    }
                }
            },
            error: function(res) {
                console.log("Error:", res);
            }
        });
    });
});

// add type
$("#addType").on("click", function(e) {
    e.preventDefault();
    var _token = $('meta[name="csrf-token"]').attr("content");
    var typeName = $("#modal-input-typeName").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        // console.log(confirm);
        if (confirm.value) {
            var formData = {
                type_name: typeName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/type/add",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }

                            // var users = '<tr>';
                            // users += '<th scope="row">' + data.data['user_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['first_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['last_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['phone'] + '</th>';
                            // users += '<th scope="row">' + data.data['department'] + '</th>';
                            // users += '<th scope="row">' + data.data['permission'] + '</th>';
                            // users += '</tr>';
                            // $('#userTableBody').append(users);
                            // $('#user-form').trigger("reset");
                            // $('#add-user-modal').modal('hide');
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                            $("#modal-input-typeName").addClass(
                                "border-danger"
                            );
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// add unit
$("#addUnit").on("click", function(e) {
    e.preventDefault();
    var _token = $('meta[name="csrf-token"]').attr("content");
    var unitName = $("#modal-input-unitName").val();
    // console.log(unitName);

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                unit_name: unitName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/unit/add",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }

                            // var users = '<tr>';
                            // users += '<th scope="row">' + data.data['user_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['first_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['last_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['phone'] + '</th>';
                            // users += '<th scope="row">' + data.data['department'] + '</th>';
                            // users += '<th scope="row">' + data.data['permission'] + '</th>';
                            // users += '</tr>';
                            // $('#userTableBody').append(users);
                            // $('#user-form').trigger("reset");
                            // $('#add-user-modal').modal('hide');
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                            $("#modal-input-unitName").addClass(
                                "border-danger"
                            );
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// add warehouse
$("#addWarehouse").on("click", function(e) {
    e.preventDefault();
    var _token = $('meta[name="csrf-token"]').attr("content");
    var warehouseName = $("#modal-input-warehouseName").val();
    // console.log(warehouseName);

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                warehouse_name: warehouseName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "/admin/material/warehouse/add",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }

                            // var users = '<tr>';
                            // users += '<th scope="row">' + data.data['user_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['first_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['last_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['phone'] + '</th>';
                            // users += '<th scope="row">' + data.data['department'] + '</th>';
                            // users += '<th scope="row">' + data.data['permission'] + '</th>';
                            // users += '</tr>';
                            // $('#userTableBody').append(users);
                            // $('#user-form').trigger("reset");
                            // $('#add-user-modal').modal('hide');
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                            $("#modal-input-warehouseName").addClass(
                                "border-danger"
                            );
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// type modal
// add dep
$("#addDep").on("click", function(e) {
    e.preventDefault();
    var _token = $('meta[name="csrf-token"]').attr("content");
    var depName = $("#modal-input-depName").val();
    // console.log(depName);

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                dep_name: depName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/department/add",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }

                            // var users = '<tr>';
                            // users += '<th scope="row">' + data.data['user_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['first_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['last_name'] + '</th>';
                            // users += '<th scope="row">' + data.data['phone'] + '</th>';
                            // users += '<th scope="row">' + data.data['department'] + '</th>';
                            // users += '<th scope="row">' + data.data['permission'] + '</th>';
                            // users += '</tr>';
                            // $('#userTableBody').append(users);
                            // $('#user-form').trigger("reset");
                            // $('#add-user-modal').modal('hide');
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                            $("#modal-input-depName").addClass("border-danger");
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// edit type
$("#editType").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var idType = $("#modal-id-type").val();
    var nameType = $("#modal-name-type").val();
    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idType,
                type_name: nameType,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/type/edit",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                $("#edit-type-form").trigger("reset");
                                $("#edit-type-modal").modal("hide");
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                            // $('#modal-input-userName').addClass('border-danger');
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// edit unit modal
$("#editUnit").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var idUnit = $("#modal-id-unit").val();
    var nameUnit = $("#modal-name-unit").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (!confirm.value) {
            return;
        }
        var formData = {
            id: idUnit,
            unit_name: nameUnit,
            _token: _token
        };

        $.ajax({
            type: "POST",
            url: "material/unit/edit",
            data: formData,
            dataType: "json",
            success: function(res) {
                const jsonData = JSON.parse(res);
                console.log(jsonData);
                if (jsonData.HEADER.status === 200) {
                    const unitName = jsonData.BODY.unit_name;
                    Swal.fire({
                        title: "สำเร็จ",
                        text:
                            "แก้ไขข้อมูลหน่วยวัสดุ " +
                            unitName +
                            " เรียบร้อยแล้ว",
                        icon: "success",
                        showConfirmButton: true,
                        focusConfirm: true
                    }).then(function(confirm) {
                        if (confirm) {
                            location.reload();
                        }

                        $("#edit-unit-form").trigger("reset");
                        $("#edit-unit-modal").modal("hide");
                    });
                } else {
                    const errors = jsonData.HEADER.error_message;
                    Object.keys(errors).forEach(function(key) {
                        var value = errors[key];
                        $(`input[name=${key}]`).addClass("border-danger");
                    });
                }
            },
            error: function(data) {
                console.log("Error:", data);
            }
        });
    });
});
// edit warehouse modal
$("#editWarehouse").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var idWarehouse = $("#modal-id-warehouse").val();
    var nameWarehouse = $("#modal-name-warehouse").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idWarehouse,
                warehouse_name: nameWarehouse,
                _token: _token
            };

            $.ajax({
                type: "POST",
                url: "/admin/material/warehouse/edit",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }
                            $("#edit-warehouse-form").trigger("reset");
                            $("#edit-warehouse-modal").modal("hide");
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});
// edit dep modal
$("#editDep").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var idDep = $("#modal-id-dep").val();
    var nameDep = $("#modal-name-dep").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idDep,
                dep_name: nameDep,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/department/edit",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm) {
                                location.reload();
                            }
                            $("#edit-dep-form").trigger("reset");
                            $("#edit-dep-modal").modal("hide");
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-type", function() {
    var type = $(this).data("type");
    $("#modal-id-type").val(type["id_type"]);
    $("#modal-id-type").prop("readonly", true);
    $("#modal-name-type").val(type["type_name"]);
});

$(document).on("click", "#delete-type", function() {
    var type = $(this).data("type");
    var idType = type["id_type"];
    var typeName = type["type_name"];
    var _token = $('meta[name="csrf-token"]').attr("content");

    Swal.fire({
        title: "คุณต้องการลบ " + typeName + " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idType,
                unit_name: typeName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/type/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-unit", function() {
    var unit = $(this).data("unit");
    $("#modal-id-unit").val(unit["id_unit"]);
    $("#modal-id-unit").prop("readonly", true);
    $("#modal-name-unit").val(unit["unit_name"]);
});

$(document).on("click", "#delete-unit", function() {
    var unit = $(this).data("unit");
    var idUnit = unit["id_unit"];
    var unitName = unit["unit_name"];
    var _token = $('meta[name="csrf-token"]').attr("content");

    Swal.fire({
        title: "คุณต้องการลบหน่วย " + unitName + " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idUnit,
                unit_name: unitName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/unit/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-warehouse", function() {
    var warehouse = $(this).data("warehouse");
    $("#modal-id-warehouse").val(warehouse["id_warehouse"]);
    $("#modal-id-warehouse").prop("readonly", true);
    $("#modal-name-warehouse").val(warehouse["warehouse_name"]);
});

$(document).on("click", "#delete-warehouse", function() {
    var warehouse = $(this).data("warehouse");
    var idWarehouse = warehouse["id_warehouse"];
    var warehouseName = warehouse["warehouse_name"];
    var _token = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: "คุณต้องการลบ " + warehouseName + " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idWarehouse,
                warehouse_name: warehouseName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "/admin/material/warehouse/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-dep", function() {
    var dep = $(this).data("dep");
    $("#modal-id-dep").val(dep["id_dep"]);
    $("#modal-id-dep").prop("readonly", true);
    $("#modal-name-dep").val(dep["dep_name"]);
});

$(document).on("click", "#delete-dep", function() {
    var dep = $(this).data("dep");
    var idDep = dep["id_dep"];
    var depName = dep["dep_name"];
    var _token = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: "คุณต้องการลบ " + depName + " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: idDep,
                dep_name: depName,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/department/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-material", function(e) {
    e.preventDefault();
    var materials = $(this).data("material");
    $("#modal-input-materialCode").val(materials["m_code"]);
    $("#modal-input-materialName").val(materials["m_name"]);
    $("#modal-input-materialUnit").val(materials["m_unit"]);
});

$("#editMaterial").on("click", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var materialCode = $("#modal-input-materialCode").val();
    var materialName = $("#modal-input-materialName").val();
    var materialUnit = $("#modal-input-materialUnit").val();

    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (!confirm.value) {
            return;
        }

        var formData = {
            m_code: materialCode,
            m_name: materialName,
            m_unit: materialUnit,
            _token: _token
        };
        // console.log(formData);
        $.ajax({
            type: "POST",
            url: "/admin/material/edit",
            data: formData,
            dataType: "json",
            success: function(res) {
                const jsonData = JSON.parse(res);
                if (jsonData.HEADER.status === 200) {
                    const materialName = jsonData.BODY.m_name;
                    Swal.fire({
                        title: "สำเร็จ",
                        text:
                            "แก้ไขข้อมูลวัสดุ " +
                            materialName +
                            " เรียบร้อยแล้ว",
                        icon: "success",
                        showConfirmButton: true,
                        focusConfirm: true
                    }).then(function(confirm) {
                        if (confirm) {
                            location.reload();
                        }

                        $("#material-form").trigger("reset");
                        $("#add-material-modal").modal("hide");
                    });
                } else {
                    const errors = jsonData.HEADER.error_message;
                    Object.keys(errors).forEach(function(key) {
                        var value = errors[key];
                        $(`input[name=${key}]`).addClass("border-danger");
                        $(`select[name=${key}]`).addClass("border-danger");
                    });
                }
            },
            error: function(errors) {
                console.log("Error:", errors);
            }
        });
    });
});

$(document).on("click", "#delete-material", function() {
    var material = $(this).data("material");
    var materialCode = material["m_code"];
    var materialName = material["m_name"];
    var _token = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title: "คุณต้องการลบ " + materialName + " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                m_code: materialCode,
                _token: _token
            };
            // console.log(formData);
            $.ajax({
                type: "POST",
                url: "material/material/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == 0) {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(confirm) {
                            if (confirm.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                },
                error: function(data) {
                    console.log("Error:", data);
                }
            });
        }
    });
});

$(document).on("click", "#edit-member", function(e) {
    e.preventDefault();
    var members = $(this).data("member");
    $("#modal-id-member").val(members["id"]);
    $("#modal-fname-member").val(members["fname"]);
    $("#modal-lname-member").val(members["lname"]);
});

$(document).on("click", "#editMember", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var firstName = $("#modal-fname-member").val();
    var lastName = $("#modal-lname-member").val();
    var id = $("#modal-id-member").val();
    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: id,
                fname: firstName,
                lname: lastName,
                _token: _token
            };
            $.ajax({
                type: "POST",
                url: "material/member/edit",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == "0") {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(res) {
                            if (res.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                }
            });
        }
    });
});

$(document).on("click", "#addMember", function() {
    var _token = $('meta[name="csrf-token"]').attr("content");
    var firstName = $("#add-fname-member").val();
    var lastName = $("#add-lname-member").val();
    Swal.fire({
        title: "กรุณาตรวจสอบข้อมูล!!",
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนบันทึก",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        focusConfirm: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                fname: firstName,
                lname: lastName,
                _token: _token
            };
            $.ajax({
                type: "POST",
                url: "material/member/add",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == "0") {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(res) {
                            if (res.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                }
            });
        }
    });
});

$(document).on("click", "#delete-member", function() {
    var members = $(this).data("member");
    var _token = $('meta[name="csrf-token"]').attr("content");
    Swal.fire({
        title:
            "คุณต้องการลบ " +
            members["fname"] +
            " " +
            members["lname"] +
            " ใช่หรือไม่ ?",
        text: "",
        icon: "warning",
        showConfirmButton: true,
        showCancelButton: true,
        cancelButtonColor: "#f5365c"
    }).then(function(confirm) {
        if (confirm.value) {
            var formData = {
                id: members["id"],
                _token: _token
            };
            $.ajax({
                type: "POST",
                url: "material/member/delete",
                data: formData,
                dataType: "json",
                success: function(data) {
                    if (data.status == "0") {
                        Swal.fire({
                            title: "สำเร็จ",
                            text: data.massage,
                            icon: "success",
                            showConfirmButton: true,
                            focusConfirm: true
                        }).then(function(res) {
                            if (res.value) {
                                location.reload();
                            }
                        });
                    } else {
                        if (data.status == 1) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        } else if (data.status == 2) {
                            Swal.fire({
                                title: "พบผิดพลาด",
                                text: data.massage,
                                icon: "error",
                                showConfirmButton: true
                            });
                        }
                    }
                }
            });
        }
    });
});
