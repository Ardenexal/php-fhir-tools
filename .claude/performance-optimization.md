---
inclusion: fileMatch
fileMatchPattern: '*Generator*.php'
---

# Performance Optimization Guidelines

## Memory Management

### Large Dataset Handling
- **Streaming Processing**: Process FHIR packages in streams rather than loading entirely into memory
- **Batch Processing**: Break large operations into smaller batches
- **Memory Monitoring**: Monitor memory usage during generation processes
- **Garbage Collection**: Explicitly unset large objects when no longer needed
- **Memory Limits**: Set appropriate PHP memory limits for large FHIR packages

### Object Lifecycle Management
```php
// Good: Release memory after processing
foreach ($structureDefinitions as $definition) {
    $this->processDefinition($definition);
    unset($definition); // Free memory immediately
}

// Bad: Keep all objects in memory
$allDefinitions = $this->loadAllDefinitions();
foreach ($allDefinitions as $definition) {
    $this->processDefinition($definition);
}
```

## I/O Optimization

### File Operations
- **Buffered Writing**: Use buffered file writing for large generated files
- **Atomic Operations**: Write to temporary files and rename for atomic updates
- **Directory Batching**: Create directory structures efficiently
- **File Locking**: Use appropriate file locking for concurrent operations

### Network Operations
- **Connection Pooling**: Reuse HTTP connections when possible
- **Parallel Downloads**: Download multiple FHIR packages concurrently
- **Compression**: Use compression for network transfers
- **Caching**: Cache downloaded FHIR packages locally

## Code Generation Optimization

### Template Processing
- **Template Caching**: Cache compiled templates to avoid recompilation
- **Lazy Loading**: Load templates only when needed
- **Template Optimization**: Optimize template logic for performance
- **Batch Generation**: Generate multiple classes in single template passes

### Output Optimization
- **Incremental Generation**: Only regenerate changed files
- **Dependency Tracking**: Track dependencies to minimize regeneration
- **Parallel Processing**: Generate multiple files concurrently when possible
- **Output Buffering**: Buffer output to reduce I/O operations

## Database and Caching

### Caching Strategies
- **Definition Caching**: Cache parsed FHIR StructureDefinitions
- **Metadata Caching**: Cache generation metadata and dependencies
- **Result Caching**: Cache generation results for unchanged inputs
- **Cache Invalidation**: Implement proper cache invalidation strategies

### Data Structure Optimization
- **Efficient Data Structures**: Use appropriate data structures for different operations
- **Index Creation**: Create indexes for frequently accessed data
- **Query Optimization**: Optimize data queries and lookups
- **Memory-Efficient Storage**: Use memory-efficient storage formats

## Profiling and Monitoring

### Performance Profiling
- **Execution Time Tracking**: Track execution time for different operations
- **Memory Usage Profiling**: Profile memory usage patterns
- **Bottleneck Identification**: Identify and address performance bottlenecks
- **Benchmark Testing**: Create benchmarks for performance regression testing

### Monitoring Integration
- **Performance Metrics**: Collect and monitor performance metrics
- **Resource Usage**: Monitor CPU, memory, and I/O usage
- **Alerting**: Set up alerts for performance degradation
- **Trend Analysis**: Analyze performance trends over time

## Concurrent Processing

### Multi-Threading Considerations
- **Thread Safety**: Ensure thread-safe operations when using concurrent processing
- **Resource Locking**: Use appropriate locking mechanisms for shared resources
- **Process Isolation**: Isolate processes to prevent interference
- **Error Handling**: Handle errors in concurrent operations properly

### Async Operations
- **Async I/O**: Use asynchronous I/O operations when available
- **Promise Handling**: Handle promises and async operations correctly
- **Callback Management**: Manage callbacks efficiently
- **Event Loop**: Understand and optimize event loop usage

## Configuration Optimization

### Runtime Configuration
- **PHP Configuration**: Optimize PHP configuration for performance
- **Memory Limits**: Set appropriate memory limits for operations
- **Execution Timeouts**: Configure reasonable execution timeouts
- **Error Reporting**: Optimize error reporting for production

### Environment Optimization
- **Development vs Production**: Use different optimizations for different environments
- **Resource Allocation**: Allocate resources appropriately for workload
- **Scaling Strategies**: Implement horizontal and vertical scaling strategies
- **Load Balancing**: Consider load balancing for high-volume operations