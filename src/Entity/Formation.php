<?php

namespace App\Entity;

use App\Entity\Niveau;
use App\Repository\FormationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=91, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $miniature;

    /**
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $videoId;


    /**
     * Niveau::class
     * @ORM\ManyToOne(targetEntity="Niveau")
     */
    private $niveau;
    
    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        // check if the image is the right size
        try{
            $picture = $this->getPicture();
            if($picture!=""){
                $imageSizeP = getimagesize($picture);
                if ($imageSizeP[0]!=640 && $imageSizeP[1]!=480){
                    $context->buildViolation("Cette image n'a pas une résolution de 640x480 ")
                        ->atPath('picture')
                        ->addViolation()
                    ;
                }    
            }    
        } catch (Exception $ex) {
            $context->buildViolation("Le format n'est pas accepté ")
                    ->atPath('picture')
                    ->addViolation();
        }
        try{
            $miniature = $this->getMiniature();
            if($miniature!=""){
                $imageSizeM = getimagesize($miniature);
                if ($imageSizeM[0]!=120 && $imageSizeM[1]!=90){
                $context->buildViolation("Cette image n'a pas une résolution de 120x90 ")
                    ->atPath('miniature')
                    ->addViolation()
                ;
                }
            }
        } catch (Exception $ex) {
            $context->buildViolation("Le format n'est pas accepté ")
                    ->atPath('miniature')
                    ->addViolation();
        }
        
        $title = $this->getTitle();
        if($title==''){
            $context->buildViolation("Insérez un titre")
                    ->atPath('title')
                    ->addViolation();
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function getPublishedAtString(): string {
        return $this->publishedAt->format('d/m/Y');     
    }    
        
    public function setPublishedAt(?DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMiniature(): ?string
    {
        return $this->miniature;
    }

    public function setMiniature(?string $miniature): self
    {
        $this->miniature = $miniature;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    public function setVideoId(?string $videoId): self
    {
        $this->videoId = $videoId;

        return $this;
    }
    
    /**
     * 
     * @return Niveau
     */
    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }
}
