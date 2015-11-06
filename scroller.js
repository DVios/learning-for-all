/**
 * Created by Anuradha Sanjeewa on 05/11/2015.
 */
function scrollWindow(cs) {
    var H,y;
    var intervaller = setInterval(move,20);
    var timeouter =  setTimeout(stop,1000);
    switch (cs) {
        // up
        case 1:
            H = -400;
            //window.scrollBy(0, -300);
            break;
        // down
        case 2:
            //window.scrollBy(0, 300);
            H = 400;
            break;
    }
    function move() {
        window.scrollBy(0,H/20);
    }
    function stop(){
        clearInterval(intervaller);
    }
}