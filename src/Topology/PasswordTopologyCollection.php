<?php
//StrictType
declare(strict_types = 1);

/*
 * Ness
 * Password component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace Ness\Component\Password\Topology;

/**
 * Collection of PasswordTopology.
 * A collection can accept only an unique topology identifier generator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyCollection
{
    
    /**
     * Registered topologies indexed by topology valued how many used
     * 
     * @var array[int|null]
     */
    private $topologies = [];
    
    /**
     * How the topologies has been generated
     * 
     * @var string
     */
    private $generatedBy;
    
    /**
     * Number of topologies setted to null
     * 
     * @var int
     */
    private $restricted = 0;
    
    /**
     * Add a topology to the collection.
     * If no previous topology has been registered, this one will determine how further registered topologies has been generated.
     * Cannot override a topology setted to null
     * 
     * @param PasswordTopology $topology
     *   Password topology
     * @param int|null $used
     *   How many times the topology has been used. Set to null for high priority.
     *   If the given topology is already registered, this number will be added to the current. By default, will add one
     */
    public function add(PasswordTopology $topology, ?int $used = 1): void
    {
        if(null === $this->generatedBy)
            $this->generatedBy = $topology->generatedBy();
        
        $registered = $this->has($topology);
        if( ($registered && null === $this->topologies[$topology->get()]) || ($this->generatedBy !== $topology->generatedBy()) )
            return;
        
        if(null === $used) {
            $this->topologies = [$topology->get() => null] + $this->topologies;
            $this->restricted++;
            
            return;
        }
        
        ($registered) ? $this->topologies[$topology->get()] += $used : $this->topologies[$topology->get()] = $used;
    }
    
    /**
     * Check if a topology is registered into the collection.
     * If the generator identifier does not correspond, it will directly return false
     * 
     * @param PasswordTopology $topology
     *   Topology to check
     * 
     * @return bool
     *   True if the given topology has been registered. False otherwise
     */
    public function has(PasswordTopology $topology): bool
    {
        return ($topology->generatedBy() !== $this->generatedBy) ? false : \array_key_exists($topology->get(), $this->topologies);
    }
    
    /**
     * Simply extract a number of topologies from the collection and return it as a new collection
     * 
     * @param int $limit
     *   Limit of topologies to extract
     * @param bool $ignoreNullTopologies
     *   All topologies setted to null will be ignored during extracting process. Therefore, they do not count for the limit
     * 
     * @return PasswordTopologyCollection
     *   New collection with extracted topologies
     */
    public function extract(int $limit, bool $ignoreNullTopologies = true): PasswordTopologyCollection
    {
        $this->sort();
        
        $collection = new self();
        if($ignoreNullTopologies)
            $limit += $this->restricted;
        
        foreach (\array_slice($this->topologies, 0, $limit) as $topology => $used) {
            $collection->add(new PasswordTopology($topology, $this->generatedBy), $used);
        }
        
        return $collection;
    }
    
    /**
     * Merge a collection into this one
     * 
     * @param PasswordTopologyCollection $collection
     *   Collection to merge
     * 
     * @return self
     *   Merged collection. Same instance, mutable
     */
    public function merge(PasswordTopologyCollection $collection): self
    {        
        if(null !== $this->generatedBy && $collection->generatedBy !== $this->generatedBy)
            return $this;
        
        foreach ($collection->topologies as $topology => $used)
            $this->add(new PasswordTopology($topology, $collection->generatedBy), $used);
        
        $this->sort();
        
        return $this;
    }
    
    /**
     * Sort collection registered.
     * Null topologies are always prioritized
     */
    private function sort(): void
    {
        $topologies = \array_slice($this->topologies, $this->restricted);
        \arsort($topologies);
        
        $this->topologies = \array_slice($this->topologies, 0, $this->restricted) + $topologies;
    }
    
}
