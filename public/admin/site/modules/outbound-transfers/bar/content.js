var currentScript = document.currentScript;

window.addEventListener('load', function() {
    // Get the navbar items and the content for this module
    var navbarOutboundTransfersListItem = document.getElementById('navbarOutboundTransfersListItem');
    var navbarTransfersListItem = document.getElementById('navbarTransfersListItem');

    // If we can find the navbarTransfersListItem ID then replace it with the navbarOutboundTransfersListItem from this module
    if (navbarOutboundTransfersListItem && navbarTransfersListItem) {
        navbarTransfersListItem.parentNode.replaceChild(navbarOutboundTransfersListItem, navbarTransfersListItem);
        navbarOutboundTransfersListItem.classList.remove('d-none');
    } else {
        var sendMoneyLink = navbarOutboundTransfersListItem.querySelector('a[href*="type=send-money"]');
        if (sendMoneyLink) {
            // Clear out existing content
            while (navbarOutboundTransfersListItem.firstChild) {
                navbarOutboundTransfersListItem.removeChild(navbarOutboundTransfersListItem.firstChild);
            }

            // Adjust classes to remove dropdown functionality
            navbarOutboundTransfersListItem.classList.remove('dropdown', 'd-none');
            sendMoneyLink.classList.remove('dropdown-item');
            sendMoneyLink.classList.add('nav-link');

            // Re-add the modified link
            navbarOutboundTransfersListItem.appendChild(sendMoneyLink);

            // Find the navbar and look for an <li> containing a partial match of the text "transfer"
            var navbar = document.querySelector("nav ul");
            var transferLi = Array.from(navbar.querySelectorAll('li')).find(li => li.textContent.toLowerCase().includes('transfer'));
            
            if (transferLi) {
                // If found, insert after the matched <li>
                transferLi.parentNode.insertBefore(navbarOutboundTransfersListItem, transferLi.nextSibling);
            } else {
                // Find the last <li> element in the navbar (excluding the last one which is this module's link)
                var items = navbar.querySelectorAll('li');
                var lastLi = items[items.length - 2]; 
            
                // If there is a last <li> element, insert one before it
                if (lastLi) {
                    navbar.insertBefore(navbarOutboundTransfersListItem, lastLi);
                } else {
                    // If there are no <li> elements, just append at the end
                    navbar.appendChild(navbarOutboundTransfersListItem);
                }
            }
        }
    }

    // Remove this script from the DOM }:)
    if (currentScript) {
        currentScript.remove();
    }
});
