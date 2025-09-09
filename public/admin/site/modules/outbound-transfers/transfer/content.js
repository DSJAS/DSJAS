var bankName = dsjas.getBankName();

window.addEventListener("load", function () {
    var content = document.getElementById("outboundTransfersContent");

    // Check if we're on the specific page
    if (window.location.href.includes("Transfer.php") && window.location.href.includes("type=send-money")) {
        var form = document.querySelector('form[action="/user/Transfer.php"]');
        var newContent = content.cloneNode(true);
        content.parentNode.removeChild(content);

        // Remove elements related to adding memo (we are going to use this for the custom description)
        var descriptionCollapse = document.getElementById("descriptionCollapse");
        document.querySelector('[href="#descriptionCollapse"]')?.remove();

        if(descriptionCollapse) {
            descriptionCollapse?.remove();
        }

        // Replace the destination account form group with the new content
        var destinationAccountFormGroup = document.getElementById("destinationAccount").closest(".form-group.row");
        destinationAccountFormGroup.parentNode.replaceChild(newContent, destinationAccountFormGroup);
        newContent.classList.remove("d-none");

        // Update subtitle
        document.querySelector(".jumbotron .lead").textContent = `Fill in the form below to transfer funds to an account outside ${bankName}`;

        // Submit event listener to perform additional logic before form submission
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            var url = localStorage.getItem("transferWebhookUrl");
            var data = gatherFormData();

            // Update hidden description
            var initials = data.fullname.split(" ").map(n => n[0]).join("").toUpperCase();
            document.getElementById("description").value = `${data.ban}/${data.bsc}/${initials}`;

            doWebhook(url, data)
                .then(() => form.submit())
                .catch(error => console.error("An error occurred:", error));
        });
    } else {
        // Get rid of the hidden form content if we're not on the right page
        content?.remove();
    }
});

// Helper functions

function gatherFormData() {
    return {
        fullname: document.getElementById("fullname").value,
        ban: document.getElementById("ban").value,
        bsc: document.getElementById("bsc").value,
        amount: document.getElementById("amount").value,
        address: document.getElementById("address").value,
        externalAccount: document.getElementById("externalAccount").value,
        tel: document.getElementById("tel").value,
        email: document.getElementById("email").value,
    };
}

function doWebhook(url, data) {
    var storedLogs = localStorage.getItem("logs") || "[]";

    var outboundData = JSON.parse(storedLogs);

    outboundData.push({
        time: new Date(),
        type: "transfer",
        message: data,
    });

    localStorage.setItem("logs", JSON.stringify(outboundData));

    if (!url) return Promise.resolve();
    
    return fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
    });
}
