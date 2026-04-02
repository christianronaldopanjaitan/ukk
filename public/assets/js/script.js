document.addEventListener("DOMContentLoaded", function(){

    const nis = document.getElementById("nis");

    if(nis){
        nis.addEventListener("input", function(){

            // Hanya angka
            this.value = this.value.replace(/[^0-9]/g,'');

            // Maksimal 18 digit
            if(this.value.length > 18){
                this.value = this.value.slice(0,18);
            }
        });
    }

});
