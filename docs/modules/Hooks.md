# Standardised Module Hooks - *developer documentation*

DSJAS themes can provide a set of "module descriptors" - which are hooks into the page which describe exactly where a module should be loaded for a specific purpose. These are the "events" which your module can hook onto to be loaded into the page.

This section in the developer manual details the standardised hooks which your module can use to be loaded into guaranteed positions in the page for a specific purpose.

## Notice on non-standard events

Although it is perfectly possible to hook onto any event at all (you can place any value you wish inside the "triggerEvent" property on your module hook), we *highly discourage* this practice. One of the design principles behind modules and their loading systems is to ensure that they work with any theme the user wishes, regardless of the content of that theme. When you use non-standard module events, you make it likely that your module will not work with certain themes.

---

## **all** - Load anywhere

The **all** event will allow your module to be loaded at any position in the page - usually in the ``head`` section. Essentially, when you subscribe to the **all** event, you are telling DSJAS that you do not care where your route is loaded, as long as it is. You can guarantee that this event will fire for every single theme possible, as it is handled by the core.

This event is usually good for non-visual elements, as it usually doesn't look good otherwise.

## Headers/footers

### **header** - Load in a title/header section

The **header** event requests that your module be loaded inside of the content for a theme's title or "heading" section of the page - if one is present. The theme should attempt to make it possible for content which is appended to the end of their heading be formatted correctly and fit in with the rest of the header.

### **footer** - Load in the footer section

The **footer** event requests that your module be loaded inside the footer section of the page - if one is present. The theme should ensure that any content added to the footer is formatted correctly and justified in a way which fits with the rest of the footer.

### **alert-area** - Load in a prominent place, suitable for alerts

The **alert-are** event requests that your module be loaded in some prominent place on the page, such that content (in particular, bootstrap alerts) are noticeable and visually outstanding to the user.

## Navigation bars

### **navbar** - Append to the navbar

The **navbar** event fires after the content in the navigation bar in the theme. This can be used to append content such as action buttons or messages to the navigation bar. The theme should ensure that content appended to this section of the navbar is formatted, justified and styled correctly and consistently.

### **nav-items** - Append to navbar links

The **nav-items** event requests that your module be loaded and styled as a navbar link or content item. The styling on any added elements should be consistent with existing navigation links and must allow for placement in the same location as existing elements in the navbar, as though it is a core part of the theme.

## Main content anchors

### **pre-content** - Prepend to content

The **pre-content** event will fire before the main body of content on the page, but after the **header** event, assuming a header is present and visually above main content (as is usually the case).

### **post-content** - Append to content

The **post-content** event will fire after the main body of content on the page, but before the **footer** event, assuming a footer is present.
