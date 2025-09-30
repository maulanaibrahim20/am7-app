$.ajaxSetup({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
});

$(document).ready(function () {
    /* Multi Select */
    $(".multiselect").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 350,

        buttonClass: "form-select",
        templates: {
            button: '<button type="button" class="multiselect dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span class="multiselect-selected-text"></span></button>',
        },
    });

    $(".simple_hide_show").on("click", function () {
        var $this = $(this);
        cs = $this.data("show-content");
        ch = $this.data("hide-content");
        cv = $this.text();

        if (cv === ch) {
            $this.text(cs);
        } else {
            $this.text(ch);
        }

        // console.log(cs);
        // console.log(ch);
        // console.log(cv);
    });

    var table = $("#dataTbl,#dataTblPop").DataTable({
        iCookieDuration: 60 * 10, // in second // Aku set 10 menit saja
        scrollX: true,
        scrollY: false,
        // "colReorder": true,
        fnDrawCallback: function () {
            var api = this.api();

            // Output the data for the visible rows to the browser's console
            // console.log( api.context[0].json);
            // console.log( api.context[0]['sum_cr'] );
            // console.log( api.context[0].json);
            // console.log( api.context[0].json.recordsTotal);
            // console.log( api.context[0].json.data.length );
            //console.log( api.context[0].json.input.search.value );
            try {
                if (
                    api.context[0].json.data.length <= 0 &&
                    api.context[0].json.recordsTotal > 0 &&
                    $("#dataTbl_filter input").val().length == null
                ) {
                    // if(api.context[0].json.input.search.value != null || api.context[0].json.input.search.value.length <= 0){
                    console.log("Clear state");
                    api.state.clear();
                    // reload page
                    location.href = window.location.href;
                    // }
                }
            } catch (err) {
                console.log("Clear state");
                api.state.clear();
                location.href = window.location.href;
            }

            if ($(".data_dt_1").length != 0) {
                $(".data_dt_1").html(api.context[0].json.data_dt_1);
            }
            if ($(".data_dt_2").length != 0) {
                $(".data_dt_2").html(api.context[0].json.data_dt_2);
            }
            if ($(".data_dt_3").length != 0) {
                $(".data_dt_3").html(api.context[0].json.data_dt_3);
            }
            if ($(".data_dt_4").length != 0) {
                $(".data_dt_4").html(api.context[0].json.data_dt_4);
            }
            if ($(".data_dt_5").length != 0) {
                $(".data_dt_5").html(api.context[0].json.data_dt_5);
            }
            if ($(".data_dt_6").length != 0) {
                $(".data_dt_6").html(api.context[0].json.data_dt_6);
            }
            if ($(".data_dt_7").length != 0) {
                $(".data_dt_7").html(api.context[0].json.data_dt_7);
            }
            if ($(".data_dt_8").length != 0) {
                $(".data_dt_8").html(api.context[0].json.data_dt_8);
            }
            if ($(".data_dt_9").length != 0) {
                $(".data_dt_9").html(api.context[0].json.data_dt_9);
            }
            if ($(".data_dt_10").length != 0) {
                $(".data_dt_10").html(api.context[0].json.data_dt_10);
            }
            if ($(".data_dt_11").length != 0) {
                $(".data_dt_11").html(api.context[0].json.data_dt_11);
            }
            if ($(".data_dt_12").length != 0) {
                $(".data_dt_12").html(api.context[0].json.data_dt_12);
            }
        },
        //"columnDefs" : [ { width: '1px', targets: 0 } ]
    });
    // Start ADD BY MAUL
    table.on("preXhr.dt", function (e, settings, data) {
        data.start_date = $("#start_date").val();
        data.end_date = $("#end_date").val();
        data.status = $("#status").val();
    });
    // End ADD BY MAUL

    /* submit the form  */
    $(document).on("submit", "#ajxForm, #ajxForm2", function (e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            error: showError,
            success: showResponse,
        });
        return false;
    });

    $(document).on("submit", "#ajxFormDelete", function (e) {
        $(this).ajaxSubmit({
            beforeSubmit: showConfirmDelete,
            error: showError,
            success: showResponse,
        });
        return false;
    });

    /* Select ALl Checkbox */
    $("#select-all").on("click", function () {
        if (this.checked) {
            $(":checkbox").each(function () {
                this.checked = true;
            });
        } else {
            $(":checkbox").each(function () {
                this.checked = false;
            });
        }
    });

    /* BS Modal Handler */
    $(document).on("click", '[data-toggle="ajaxModal"]', function (e) {
        e.preventDefault();
        var $this = $(this),
            $remote = $this.data("remote") || $this.attr("href");

        // reset class dialog biar tidak numpuk
        var $dialog = $("#ajaxModal .modal-dialog");
        $dialog.removeClass("modal-sm modal-lg modal-xl");

        // cek apakah ada data-size
        var modalSize = $this.data("size");
        if (modalSize) {
            $dialog.addClass("modal-" + modalSize); // contoh: data-size="lg" → modal-lg
        }

        // set title & content
        $("#ajaxModal .modal-title").html($this.data("title") || "");
        $("#ajaxModal .modal_content").load($remote);

        $("#ajaxModal").modal({ backdrop: "static", keyboard: false });
        $("#ajaxModal").modal("show");
        return false;
    });

    $("#ajaxModal").on("hidden.bs.modal", function () {
        $("#ajaxModal .modal_content").html(
            '<center><img id="img-loader" src="' +
                location.protocol +
                "//" +
                location.hostname +
                '/assets/svg/loading.svg" height="40" alt="Loading.." /></center>'
        );
    });
});
function _reload_datatables() {
    $("#dataTbl").DataTable({
        iCookieDuration: 60 * 10, // in second // Aku set 10 menit saja
        bDestroy: true,
        fnDrawCallback: function () {
            var api = this.api();
            if ($(".data_dt_1").length != 0) {
                $(".data_dt_1").html(api.context[0].json.data_dt_1);
            }
            if ($(".data_dt_2").length != 0) {
                $(".data_dt_2").html(api.context[0].json.data_dt_2);
            }
            if ($(".data_dt_3").length != 0) {
                $(".data_dt_3").html(api.context[0].json.data_dt_3);
            }
            if ($(".data_dt_4").length != 0) {
                $(".data_dt_4").html(api.context[0].json.data_dt_4);
            }
            if ($(".data_dt_5").length != 0) {
                $(".data_dt_5").html(api.context[0].json.data_dt_5);
            }
            if ($(".data_dt_6").length != 0) {
                $(".data_dt_6").html(api.context[0].json.data_dt_6);
            }
            if ($(".data_dt_7").length != 0) {
                $(".data_dt_7").html(api.context[0].json.data_dt_7);
            }
            if ($(".data_dt_8").length != 0) {
                $(".data_dt_8").html(api.context[0].json.data_dt_8);
            }
            if ($(".data_dt_9").length != 0) {
                $(".data_dt_9").html(api.context[0].json.data_dt_9);
            }
            if ($(".data_dt_10").length != 0) {
                $(".data_dt_10").html(api.context[0].json.data_dt_10);
            }
            if ($(".data_dt_11").length != 0) {
                $(".data_dt_11").html(api.context[0].json.data_dt_11);
            }
            if ($(".data_dt_12").length != 0) {
                $(".data_dt_12").html(api.context[0].json.data_dt_12);
            }
        },
    });
}
function showError(data) {
    if (!$.isEmptyObject(data)) {
        if (data.responseJSON.message) {
            $errElm = $("#modal-message");
            $errElm.show().html("");
            if ($errElm.length) {
                $errElm.append(
                    '<div class="alert alert-danger alert-important">' +
                        data.responseJSON.message +
                        "</div>"
                );
            }
        }
    } else {
        alert("Error Occurred : Ajax Error or Form action did not exist!");
        return false;
    }
}
function showConfirmDelete(formData, jqForm, options) {
    // jqForm adalah elemen form yang akan dikirim
    let proceed = false;

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // submit form via ajaxSubmit
            jqForm.ajaxSubmit({
                error: showError,
                success: showResponse,
            });
        }
    });

    // jangan submit otomatis, karena submit akan dilakukan di dalam then()
    return false;
}
function showResponse(data) {
    var $formElm = $("#ajxForm");
    var $elModal = $("#ajaxModal");
    var $errElm = $("#ajxForm_message");
    $errElm.show().html("");
    if (!$.isEmptyObject(data.error)) {
        if ($elModal.length && $("body").hasClass("modal-open")) {
            /* bila ada modal */
            $errElm = $("#modal-message");
            $errElm.show().html("");
        }
        /* Gagal */
        var msg = data.error;
        var x = "";
        $.each(msg, function (key, value) {
            x = x + "<li>" + value + "</li>";
        });
        if ($errElm.length) {
            $errElm.append(
                '<div class="alert alert-danger alert-important"><ul>' +
                    x +
                    "</ul></div>"
            );
        }
    } else {
        var msg = data.message;
        if (Array.isArray(msg)) {
            $.each(msg, function (key, value) {
                if ($errElm.length) {
                    $errElm.append(
                        '<div class="alert alert-success alert-important">' +
                            value +
                            "</div>"
                    );
                }
            });
        } else {
            if ($errElm.length) {
                $errElm.append(
                    '<div class="alert alert-success alert-important">' +
                        msg +
                        "</div>"
                );
            }
        }

        /* bool - form reset */
        if ($formElm.length && $formElm.attr("data-ajxForm-reset") != "false") {
            $formElm[0].reset();
            window.location.reload();
        }

        /* Bila ada datatables */
        if ($("#dataTbl").length != 0) {
            // $('#dataTbl').DataTable({"bDestroy":true });
            _reload_datatables();
        }

        /* Bila ada datatables fixed*/
        if ($("#dataTblFixed").length != 0) {
            _reload_datatables();
        }

        /* Bila ada modal - Hide */
        if ($elModal.length && $("body").hasClass("modal-open")) {
            //$elModal.modal('hide').data( 'bs.modal', null );;
            $elModal.modal("toggle");
            $("body").removeClass("modal-open");
        }
    }
    $errElm.delay(4000).fadeOut("slow");
}

// Custom By Maul
document.addEventListener("DOMContentLoaded", function () {
    // Tampilkan loader
    const progressLoader = document.querySelector(".progress-loader");
    progressLoader.classList.add("loading");

    // Sembunyikan loader setelah halaman selesai dimuat
    window.addEventListener("load", function () {
        setTimeout(() => {
            progressLoader.classList.remove("loading");
        }, 500);
    });

    // Opsional: Tambahkan loader saat navigasi/ajax
    document.addEventListener("ajaxStart", function () {
        progressLoader.classList.add("loading");
    });

    document.addEventListener("ajaxStop", function () {
        progressLoader.classList.remove("loading");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("menuSearch");

    if (searchInput) {
        searchInput.addEventListener("input", function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const menuItems = document.querySelectorAll(
                ".nav-item .dropdown-item"
            );

            menuItems.forEach(function (item) {
                const text = item.textContent.toLowerCase();
                const listItem = item.closest(".nav-item");

                if (text.includes(searchTerm) || searchTerm === "") {
                    item.style.display = "block";
                    if (listItem) listItem.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });

            // Show/hide dropdown menus based on visible items
            document
                .querySelectorAll(".nav-item.dropdown")
                .forEach(function (dropdown) {
                    const visibleItems = dropdown.querySelectorAll(
                        '.dropdown-item[style="display: block"], .dropdown-item:not([style*="display: none"])'
                    );
                    if (visibleItems.length === 0 && searchTerm !== "") {
                        dropdown.style.display = "none";
                    } else {
                        dropdown.style.display = "block";
                    }
                });
        });
    }
});
