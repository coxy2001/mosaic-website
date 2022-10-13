var sectors = [
    {
        "name": "Information Technology",
        "selected": 1
    },
    {
        "name": "Communication Services",
        "selected": 1
    },
];

var allselectedSectors = true;
var checkboxesSectors = document.getElementById("checkboxes-sector");
var expandedSectors = false;


addSector();

function addSector() {
    checkboxesSectors.innerHTML += '<label/><input id="600" type="checkbox" onclick="selectSector(600)" checked/>All Sectors</label>';
    for (i = 0; i < sectors.length; i++) {
        if(sectors[i].selected == 1){
            checkboxesSectors.innerHTML += '<label/><input id="sec-'+ i +'" type="checkbox" onclick="selectSector("sec-'+i+'") checked/>' + sectors[i].name + '</label>';
        }else{
            checkboxesSectors.innerHTML += '<label/><input id="sec-'+ i +'" type="checkbox" onclick="selectSector("sec-'+i+'")/>' + sectors[i].name + '</label>';
        }
    }
    updateCheckboxSector();
}


function showCheckboxesSector() {
  if (!expandedSectors) {
    checkboxesSectors.style.display = "block";
    expandedSectors = true;
  } else {
    checkboxesSectors.style.display = "none";
    expandedSectors = false;
  }
}


function updateCheckboxSector(){
    //check URL if we already have countries selected
    var url = new URL(window.location.href);

    if(url.searchParams.get("sector")){
        const sectorList = url.searchParams.get("sector").split(",");

        for (let i = 0; i < sectors.length; i++) {

            if(sectorList.includes(sectors[i].name)){
                sectors[i].selected = 1;
            }
            else{
                sectors[i].selected = 0;
            }
            
        }

        
        allselectedSectors = false;
        document.getElementById("600").checked = false;

    }

    const inputElementsSectors = checkboxesSectors.querySelectorAll("input");

    for (let i = 1; i < inputElementsSectors.length; i++) {            //Ignore the first one since its all countries
        document.getElementById(inputElementsSectors[i].id).checked = sectors[inputElementsSectors[i].id.substring(4)].selected;
    }
}

function getSelectedSectors(){
    var selectedSectors = new Array();

    sectors.forEach(sector => {
        if(sector.selected == 1){
            selectedSectors.push(sector.name);
        }
    });

    return selectedSectors;
}

function selectSector(id){
    if(id == "600" && document.getElementById("600").checked == true){
        sectors.forEach(sector => {
            sector.selected = 1
        });
        allselectedSectors = true;
    }else if(id == "600" && document.getElementById("600").checked == false){
        sectors.forEach(sector => {
            sector.selected = 0
        });
        allselectedSectors = false;
    }else if(id.substring(4) > sectors.length()){
        console.log("Sector out of list");
    }else{
        if(sectors[id.substring(4)].selected == 1){        //unselect sector
            sectors[id.substring(4)].selected = 0;
        }else{
            sectors[id.substring(4)].selected = 1;         //select sector
        }
    }

    updateCheckboxSector();
}

function sendSectors() {                      //Refresh the website with the country query 
    var url = new URL(window.location.href);

    if(allselectedSectors){
        window.location.replace(url);

    }else{
        url.searchParams.set("sector", getSelectedSectors());
        window.location.replace(url);
    }
}
