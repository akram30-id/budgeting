const module = $("#module");

const navLinkModule = $(".nav-link");

if (module.data("module_name") == "") {
    $('.nav-link').removeClass('active');
    $('.nav-link[data-module_name="dashboard"]').addClass('active');
} else {
    $('.nav-link').removeClass('active');
    $(`.nav-link[data-module_name="${module.data("module_name")}"]`).addClass('active');
}
