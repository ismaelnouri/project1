use League\Csv\Writer;
use League\Csv\Reader;    
        

        
         /**
    * @Route("/ajouter", name="ajouter")
    */
   //pour ajouter 
   //La table concerner contient la liste des elements
   //

   public function Ajouter(Request $request, EntityManagerInterface $manager)
   {  
      //dump($request->request->all());
      if($request->request->count()>0)
        {
    

                    $file = $request->files->get('nom_fichier');

                    $stream = fopen($file,'r');
                    $csv = Reader::createFromStream($stream);
                    $csv->setDelimiter(';');
                    $csv->setHeaderOffset(0);

                    foreach($csv as $row)
                    {
                    

                        $row['nom de la colonne']
                            

                    }
                   

               

                            

                    

           
           

   
        }
   }