<?php 
class PreviewProvider{

    private $con;
    private $username;

    public function __construct($con, $username){
        $this->con = $con;
        $this->username = $username;
    }

    public function createCategoryPreviewVideo($categoryId){
        $entittiesArray=EntityProvider::getEntites($this->con,$categoryId,1);
        if(sizeof($entittiesArray)==0){
            ErrorMessage::show("No entities to display");
        }

        return $this->createPreviewVideo($entittiesArray[0]);
    }

    public function createTvShowPreviewVideo($entity){
        $entittiesArray=EntityProvider::GetTvShows($this->con,null,1);
        if(sizeof($entittiesArray)==0){
            ErrorMessage::show("No tv shows to display");
        }

        return $this->createPreviewVideo($entittiesArray[0]);
    }
    public function createMoviesPreviewVideo($entity){
        $entittiesArray=EntityProvider::GetMovies($this->con,null,1);
        if(sizeof($entittiesArray)==0){
            ErrorMessage::show("No movies to display");
        }

        return $this->createPreviewVideo($entittiesArray[0]);
    }

    public function createPreviewVideo($entity){
        if($entity == null){
            $entity = $this->getRandomEntity();
        }

        $id = $entity->getId();
        $name = $entity->getName();
        $thumbnail = $entity->getThumbnail();
        $preview = $entity->getPreview();

        
        
        $videoId = VideoProvider::getEntityVideoForUser($this->con,$id, $this->username);

        $video = new Video($this->con, $videoId);
        $inProgress = $video->isInProgress($this->username);
        $playButtonText = $inProgress ? "Continue watching" : "Play";
        $seasonEpisode = $video->getSeasonAndEpisode();
        $subHeading = $video->isMovie() ? "" : "<h4>$seasonEpisode</h4>";
        return "<div class='previewContainer'>
            <img src='$thumbnail' class='previewImage' hidden/>

            <video autoplay muted class='previewVideo' onended='previewEnd()'>
                <source src='$preview' type='video/mp4' >
            </video>

            <div class='previewOverlay'>

            <div class='mainDetails'>
                <h3>$name</h3>
                $subHeading
                <div class='buttons'>
                    <button onclick='watchVideo($videoId)'><i class='fas fa-play'></i> $playButtonText</button>
                    <button onclick='volumeToggle(this)'><i class='fas fa-volume-mute'></i> </button>
                </div>
            </div>

            </div>
        
        </div>";

    }

    public function createEntityPreviewSquare($entity){
        $id = $entity->getId();
        $thumbnail = $entity->getThumbnail();
        $name = $entity->getName();

        return "<a href='entity.php?id=$id'>
            <div class='previewContainer small'>
                <img src='$thumbnail' title='$name'>
            </div>

        </a>";
    }
    
    

    private function getRandomEntity(){
       $entity = EntityProvider::getEntites($this->con, null, 1);
       return $entity[0];
    }
}

?>