$(document).ready(function () {
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y"
    });
    $("#province").on("change", function () {
        let provinceId = $(this).val();
        $("#city").empty().trigger("change");

        if (provinceId) {
            $.ajax({
                url: "/api/v1/cities/" + provinceId,
                type: "GET",
                success: function (cities) {
                    let citySelect = $("#city");
                    citySelect.append('<option value="">Select City</option>');
                    $.each(cities.data, function (key, city) {
                        citySelect.append(
                            `<option value="${city.id}">${city.name}</option>`
                        );
                    });
                    citySelect.trigger("change");
                },
            });
        }
    });

    $("#organisation_type").on("change", function () {
        let typeId = $(this).val();
        $("#organisation").empty().trigger("change");

        if (typeId) {
            $.ajax({
                url: "/api/v1/organisations/" + typeId,
                type: "GET",
                success: function (organisations) {
                    let organisationSelect = $("#organisation");
                    organisationSelect.append(
                        '<option value="">Select Organisation</option>'
                    );
                    $.each(organisations.data, function (key, organisation) {
                        organisationSelect.append(
                            `<option value="${organisation.id}">${organisation.name}</option>`
                        );
                    });
                    organisationSelect.trigger("change");
                },
            });
        }
    });
});

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById("profile-preview").src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function togglePassword(fieldId, icon) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
