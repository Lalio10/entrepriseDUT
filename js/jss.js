//// Animation chargement de page 
$(window).on("load", function(){
  $(".loader").fadeOut("slow");
})

/// Class Table exporter  ///
  
class TableCSVExporter {
  //Constructeur //
  constructor(table,includeHeaders=true)
  {
      this.table= table;
      this.rows= Array.from(table.querySelectorAll("tr"));
        
      if (!includeHeaders && this.rows[0].querySelectorAll("th").length)
      {
        this.rows.shift();
      }
    }
    //Fonction permettant de convertir un tableau en fichier CSV //
    //Retourne un tableau //
    convertToCSV()
    {
      const lines =[];
      const numCols = this._findLongestRowLength();

      for(const row of this.rows) 
      {
        let line ="";
        for (let i =0 ; i < numCols; i++)
        {
          if(row.children[i]!==undefined){
            
            line += TableCSVExporter.parseCell(row.children[i]);
          }
    
          line += (i !== (numCols -1) ) ? ";" : "";
        }
        lines.push(line);
      }
      return lines.join("\n");
    }
    // Trouve la taille de chaque ligne 
    _findLongestRowLength()
    {
      return this.rows.reduce((l,row) => row.childElementCount > l ? row.childElementCount : l, 0);

    }
    // Crée des cellules avec un contenue
    static parseCell(tableCell)
    {
      let parsedValue = tableCell.textContent;
      console.log(parsedValue);
      // Replace all double quotes with two double quotes
        parsedValue= parsedValue.replace(/"/g, '""');
      
      //Remplacement de caractères spéciaux
        //caractères spéciaux en a 
          parsedValue= parsedValue.replace(/à/g,'a');
          parsedValue= parsedValue.replace(/â/g,'a');
          
          parsedValue= parsedValue.replace(/À/g,'A');
          parsedValue= parsedValue.replace(/À/g,'A');

        //caractères spéciaux en e
          parsedValue= parsedValue.replace(/é/g,'e');
          parsedValue= parsedValue.replace(/ê/g,'e');
          parsedValue= parsedValue.replace(/è/g,'e');
          parsedValue= parsedValue.replace(/ë/g,'e');

          parsedValue= parsedValue.replace(/É/g,'E');
          parsedValue= parsedValue.replace(/Ê/g,'E');
          parsedValue= parsedValue.replace(/È/g,'E');
          parsedValue= parsedValue.replace(/Ë/g,'E');

        //caractères spéciaux en i
          parsedValue= parsedValue.replace(/î/g,'i');
          parsedValue= parsedValue.replace(/ï/g,'i');

          parsedValue= parsedValue.replace(/î/g,'I');
          parsedValue= parsedValue.replace(/Ï/g,'I');

        //caractères spéciaux en o
          parsedValue= parsedValue.replace(/ô/g,'o');
          parsedValue= parsedValue.replace(/ö/g,'o');

          parsedValue= parsedValue.replace(/Ô/g,'O');
          parsedValue= parsedValue.replace(/Ö/g,'O');

        //caractères spéciaux en u
          parsedValue= parsedValue.replace(/ù/g,'u');
          parsedValue= parsedValue.replace(/û/g,'u');
          parsedValue= parsedValue.replace(/ü/g,'u');

          parsedValue= parsedValue.replace(/Ù/g,'U');
          parsedValue= parsedValue.replace(/Û/g,'U');
          parsedValue= parsedValue.replace(/Ü/g,'U');

        //caractères spéciaux en ç
          parsedValue= parsedValue.replace(/ç/g,'c');

          parsedValue= parsedValue.replace(/Ç/g,'C');

        //caractères spéciaux en œ (oe)
          parsedValue= parsedValue.replace(/œ/g,'oe');

          parsedValue= parsedValue.replace(/Œ/g,'OE');
      // If value contains comma, new-line or double-quote, enclose in double quotes
      parsedValue = /[",\n]/.test(parsedValue) ? `"${parsedValue}"` : parsedValue ; 
      
      return parsedValue;
    }
}