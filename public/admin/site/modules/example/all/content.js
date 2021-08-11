console.log("This is text in the console which will be loaded on every page! Congrats!");
console.log(dsjas.getBankName());
console.log(dsjas.getBankUrl())
console.log(dsjas.getThemeName());

if (dsjas.accounts.isLoggedIn()) {
    console.log(dsjas.accounts.getUsername());
    console.log(dsjas.accounts.getBankAccounts());
}